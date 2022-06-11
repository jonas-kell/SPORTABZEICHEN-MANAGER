import axios from 'axios';
import { SessionStorage } from 'quasar';

// Add a response interceptor (basically a middleware)
axios.interceptors.response.use(
  async function (response) {
    response.data = await recreateObjectFromCache(response.data);

    return response;
  },
  function (error) {
    // Do something with response error
    return Promise.reject(error);
  }
);

export default axios;

const STORAGE_CACHE_PREFIX = 'CACHE_STORAGE_';

async function recreateObjectFromCache<Type>(object: Type): Promise<Type> {
  let breakLoop = false;

  while (!breakLoop) {
    const hashesMap = getHashesFromObjectRecursively<Type>(object);
    const hashesToGet = [] as string[];

    Object.keys(hashesMap).forEach((hash) => {
      if (!hashesMap[hash]) {
        // key is not present in cache
        hashesToGet.push(hash);
      }
    });

    let data = {} as { [key: string]: string };
    if (hashesToGet.length > 0) {
      data = (
        await axios.get<{ [key: string]: string }>(
          'api/cache_element/' + encodeURIComponent(JSON.stringify(hashesToGet))
        )
      ).data;
    }

    breakLoop = true;
    const objectMap = {} as { [key: string]: unknown };
    Object.keys(hashesMap).forEach((hash) => {
      breakLoop = false;
      if (hashesMap[hash]) {
        // key is in local storage
        objectMap[hash] = SessionStorage.getItem(STORAGE_CACHE_PREFIX + hash);
      } else {
        // key was just requested
        objectMap[hash] = JSON.parse(data[hash]);
        SessionStorage.set(STORAGE_CACHE_PREFIX + hash, objectMap[hash]);
      }
    });

    object = insertValuesToHashesInObjectRecursively<Type>(object, objectMap);
  }

  return object;
}

function getHashesFromObjectRecursively<Type>(object: Type): {
  [key: string]: boolean | string;
} {
  let hashes = {} as { [key: string]: boolean | string };

  if (object instanceof Array) {
    for (let index = 0; index < object.length; index++) {
      hashes = {
        ...hashes,
        ...getHashesFromObjectRecursively<Type>(object[index]),
      };
    }
  } else if (object instanceof Object) {
    if (isHash(object)) {
      // piggiback case
      // data for sure not yet cached

      // cache data here already
      SessionStorage.set(
        STORAGE_CACHE_PREFIX + (object as unknown as { hash: string })['hash'],
        JSON.parse((object as unknown as { data: string })['data'])
      );

      // tell rest of the calls the data is cached
      return {
        [(object as unknown as { hash: string })['hash']]: true,
      };
    }

    Object.keys(object).forEach((key) => {
      hashes = {
        ...hashes,
        ...getHashesFromObjectRecursively<Type>(
          (object as unknown as { [key: string]: Type })[key]
        ),
      };
    });
  } else {
    if (isHash(object)) {
      const cacheKey = STORAGE_CACHE_PREFIX + (object as unknown as string);
      return { [object as unknown as string]: SessionStorage.has(cacheKey) };
    }
  }

  return hashes;
}

function insertValuesToHashesInObjectRecursively<Type>(
  object: Type,
  objectMap: { [key: string]: unknown }
): Type {
  if (object instanceof Array) {
    const res = [] as Type[];
    for (let index = 0; index < object.length; index++) {
      res.push(
        insertValuesToHashesInObjectRecursively<Type>(object[index], objectMap)
      );
    }
    return res as unknown as Type;
  } else if (object instanceof Object) {
    if (isHash(object)) {
      // piggiback case
      return objectMap[(object as unknown as { hash: string })['hash']] as Type;
    }

    const res = {} as { [key: string]: Type };
    Object.keys(object).forEach((key) => {
      res[key] = insertValuesToHashesInObjectRecursively<Type>(
        (object as unknown as { [key: string]: Type })[key],
        objectMap
      );
    });
    return res as unknown as Type;
  } else {
    if (isHash(object)) {
      return objectMap[object as unknown as string] as Type;
    } else {
      return object;
    }
  }
}

function isHash(element: unknown): boolean {
  // piggiback data
  if (element instanceof Object) {
    return (
      'hash' in element &&
      'data' in element &&
      Object.keys(element).length == 2 &&
      isHash(element['hash'])
    );
  }

  // hash strings
  const st = String(element);

  const arr = st.split('$', 2);
  return (
    arr.length == 2 &&
    arr[0].length == 32 &&
    !isNaN(arr[1] as unknown as number)
  );
}
