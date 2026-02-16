# Project Overview

## 概要
Laravel 12 + React(TypeScript) のボイラープレートプロジェクト（ECサイト）。
管理画面(Blade + vanilla JS)とフロント画面(React SPA)の2つのフロントエンドを持つ。

## Tech Stack

### Backend
- PHP 8.2+ / Laravel 12
- 認証: JWT (tymon/jwt-auth), Laravel Fortify, Sanctum, Socialite (Google OAuth)
- DB: MySQL
- その他: DomPDF, Maatwebsite Excel, Stripe決済

### Frontend (Admin)
- Blade templates + vanilla JavaScript + SASS
- AdminLTE 4, Bootstrap 5, jQuery
- FontAwesome, Highcharts

### Frontend (Front)
- React 19 + TypeScript (SPA)
- React Router DOM v6
- Formik + Yup (フォーム/バリデーション)
- Tailwind CSS 4
- Stripe React SDK

### Build & Dev Tools
- Vite (ビルド)
- Vitest + Testing Library (JSテスト)
- PHPUnit 11 (PHPテスト)
- Pint (PHPフォーマッタ, preset: laravel)
- Rector (PHPリファクタリング)
- ESLint + Prettier (JS/TSリント・フォーマット)
- blade-formatter (Bladeテンプレートフォーマッタ)
- PHPStan + Larastan level 6 (静的解析)
- Storybook 8 (コンポーネントカタログ)

### Infrastructure
- Docker (docker-compose)
- コンテナ: laraec-app, laraec-mysql
- Makefileで操作
- AWS デプロイ対応 (ECR, CloudFormation)

## ドメインエンティティ
- User, Admin, Stock, Cart, Order, OrderStock, Contact, Image, ImportHistory, MonthlySale

## 認証
- フロント: JWT認証 (tymon/jwt-auth)
- 管理画面: Session認証
- Google OAuth ソーシャルログイン対応
