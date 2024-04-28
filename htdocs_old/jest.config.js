/**
 * Jest Configuration
 */
const { pathsToModuleNameMapper } = require("ts-jest/utils");
const { readFileSync } = require("fs");
const { parse } = require("jsonc-parser");
// extendsを自動的に解決してマージできないため、compilerOptions.pathsを書いているファイルを指定する
const { compilerOptions } = parse(readFileSync("./tsconfig.json").toString());
const moduleNameMapper = pathsToModuleNameMapper(compilerOptions.paths, {
    prefix: "<rootDir>/"
});

module.exports = {
    preset: "ts-jest",
    testEnvironment: "jsdom",
    moduleNameMapper,
    // moduleNameMapper: {
    //     "^@/(.+)": "<rootDir>/resources/src/front/ts/$1"
    // },
    globals: {
        "ts-jest": {
            tsconfig: "tsconfig.jest.json"
        }
    }
};
