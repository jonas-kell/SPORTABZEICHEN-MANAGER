// very beautiful code to unify the language objects from multiple files with prefixes

const messages = {} as { [key: string]: string };

const files = (
  require as unknown as {
    context: (p1: string, p2: boolean, p3: RegExp) => { keys: () => string[] };
  }
).context('./', true, /\.ts$/i);

files.keys().forEach((key) => {
  if (!key.includes('index')) {
    const fileEntries = (
      files as unknown as (param: string) => {
        default: Record<string, string>;
      }
    )(key).default;

    const fileName = (/\.\/(.*?)\.ts$/.exec(key) ?? ['', ''])[1];

    Object.keys(fileEntries).forEach((entry) => {
      messages[fileName + '.' + entry] = fileEntries[entry];
    });
  }
});

export default messages;
