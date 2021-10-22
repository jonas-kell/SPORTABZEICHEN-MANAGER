module.exports = {
    // override vscode settings
    // Notice: It only affects the settings used by Vetur.
    settings: {
        "vetur.useWorkspaceDependencies": true,
        "vetur.experimental.templateInterpolationService": true,
    },
    // support monorepos
    projects: [
        {
            // Where is your project?
            // It is relative to `vetur.config.js`.
            root: "./frontend",
            // Where is `package.json` in the project?
            // We use it to determine the version of vue.
            // It is relative to root property.
            package: "./package.json",
            // Where is TypeScript config file in the project?
            // It is relative to root property.
            tsconfig: "./tsconfig.json",
            // Where is vetur custom snippets folders?
            snippetFolder: "./../.vscode/vetur/snippets",
            globalComponents: [],
        },
    ],
};
