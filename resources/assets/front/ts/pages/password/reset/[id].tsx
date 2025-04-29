import React, { useState } from "react";
import CSRFToken from "@/components/atoms/CSRFToken";
import RequestToken from "@/components/atoms/RequestToken";
import SessionAlert from "@/components/atoms/SessionAlert";
import BasicLayout from "@/components/templates/BasicLayout";
import useAppRoot from "@/stores/useAppRoot";
import TextInput from "@/components/atoms/TextInput";

const ResetForm = () => {
    const appRoot = useAppRoot();
    if (!appRoot) return <></>;

    const [email, setEmail] = useState<string>("");

    const handleSetEmail = (value: string) => {
        setEmail(value);
    };

    return (
        <BasicLayout title="パスワード変更">
            <div className="bg-white p-6 rounded-md shadow-md ">
                <SessionAlert target="status" />
                <form
                    method="POST"
                    action="/reset-password"
                    id="login-form"
                >
                    <CSRFToken />
                    <RequestToken />
                    <div className="mx-auto my-5 w-100">
                        <TextInput
                            identity="email"
                            controlType="email"
                            label="メールアドレス"
                            defaultValue={email}
                            action={handleSetEmail}
                            autoFocus={true}
                            className="mb-5"
                        />
                        <TextInput
                            identity="password"
                            controlType="password"
                            autoComplete="new-password"
                            label="新しいパスワード"
                            className="mb-5"
                        />
                        <TextInput
                            identity="password_confirmation"
                            controlType="password"
                            autoComplete="new-password"
                            label="新しいパスワード(確認)"
                            className="mb-5"
                        />
                        <div className="mt-3 text-center">
                            <button type="submit" className="btn btn-primary mr-5">
                                パスワードを変更する
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </BasicLayout>
    );
};

export default ResetForm;
