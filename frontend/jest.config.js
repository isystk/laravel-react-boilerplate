const nextJest = require("next/jest");

const createJestConfig = nextJest({
    // next.config.jsとテスト環境用の.envファイルが配置されたディレクトリをセット。基本は"./"で良い。
    dir: "./",
});

// Jestのカスタム設定を設置する場所。従来のプロパティはここで定義。
const customJestConfig = {
    // jest.setup.jsを作成する場合のみ定義。
    setupFilesAfterEnv: ["<rootDir>/jest.setup.js"],
    moduleNameMapper: {
        // aliasを定義 （tsconfig.jsonのcompilerOptions>pathsの定義に合わせる）
        "^@/(.*)$": "<rootDir>/src/$1",
    },
    testEnvironment: "jest-environment-jsdom",
};

// createJestConfigを定義することによって、本ファイルで定義された設定がNext.jsの設定に反映されます
module.exports = createJestConfig(customJestConfig);