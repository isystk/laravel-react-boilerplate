import React, { FC } from "react";
import { Link } from "react-router-dom";
import { Url } from "@/constants/url";

const LoginButton: FC = () => (
    <div className="form-group mt-3">
        <div className="text-center">
            <button type="submit" color="primary">
                ログイン
            </button>
            <Link to={Url.PASSWORD_RESET} className="btn btn-link">
                パスワードを忘れた方
            </Link>
        </div>
    </div>
);
export default LoginButton;
