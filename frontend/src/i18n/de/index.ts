// very beautiful code to unify the language objects from multiple files with prefixes

const messages = {} as { [key: string]: string };

const files = import.meta.globEager('./*.ts');

Object.keys(files).forEach((key) => {
  if (!key.includes('index')) {
    const fileEntries = files[key].default;

    const fileName = (/\.\/(.*?)\.ts$/.exec(key) ?? ['', ''])[1];

    Object.keys(fileEntries).forEach((entry) => {
      messages[fileName + '.' + entry] = fileEntries[entry];
    });
  }
});

export default messages;
