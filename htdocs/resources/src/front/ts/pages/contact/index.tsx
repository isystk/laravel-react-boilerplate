import * as React from "react";
import * as Yup from "yup";
import { FC } from "react";
import { FormGroup, Label, Input } from "reactstrap";
import { Formik, Form } from "formik";
import CSRFToken from "@/components/Elements/CSRFToken";
import Layout from "@/components/Layout";
import MainService from "@/services/main";
import { KeyValue } from "@/services/const";
import ImageFileInput from "@/components/Elements/ImageFile";

type Props = {
    appRoot: MainService;
};

const ContactCreate: FC<Props> = ({ appRoot }) => {
    const auth = appRoot.auth;
    const consts = appRoot.const.data;

    const handleSubmit = async values => {
        console.log(values);

        // // 入力したお問い合わせ内容を送信する。
        // const response = await API.post(API_ENDPOINT.CONTACT_STORE, values);
        //
        // if (response.result) {
        //     // 完了画面を表示する
        //     navigate(Url.CONTACT_COMPLETE);
        // }
    };

    const initialValues = {
        your_name: auth.name || "",
        email: auth.email || "",
        gender: "",
        age: "",
        title: "",
        contact: "",
        url: "",
        imageBase64: "",
        caution: 0
    };

    const validation = Yup.object().shape({
        your_name: Yup.string()
            .max(20, "お名前は20文字以下を入れてください")
            .required("お名前を入力してください"),
        email: Yup.string()
            .email("メールアドレスを正しく入力してしてください")
            .max(255, "メールアドレスは255文字以下を入れてください")
            .required("メールアドレスを入力してください"),
        gender: Yup.number().required("性別を選択してください"),
        age: Yup.number().required("年齢を選択してください"),
        title: Yup.string()
            .max(50, "タイトルは50文字以下を入れてください")
            .required("タイトルを入力してください"),
        contact: Yup.string()
            .max(200, "タイトルは200文字以下を入れてください")
            .required("本文を入力してください"),
        url: Yup.string().url("URLを正しく入力してください"),
        imageBase64: Yup.string().required("画像を選択してください"),
        caution: Yup.array().min(1, "注意事項に同意してください")
    });

    return (
        <Layout appRoot={appRoot} title="お問い合わせ">
            <main className="main">
                <div className="container">
                    <div className="row justify-content-center">
                        <div className="col-md-8">
                            <div className="card">
                                <div className="card-header">
                                    お問い合わせ登録
                                </div>
                                <div className="card-body">
                                    <Formik
                                        initialValues={initialValues}
                                        onSubmit={handleSubmit}
                                        validationSchema={validation}
                                    >
                                        {({
                                            handleChange,
                                            handleBlur,
                                            values,
                                            errors,
                                            touched
                                        }) => (
                                            <Form>
                                                <CSRFToken appRoot={appRoot} />
                                                <FormGroup className="form-section">
                                                    <div className="form-section-wrap">
                                                        <Label className="item-name">
                                                            お名前を入力してください
                                                            <span className="required">
                                                                必須
                                                            </span>
                                                        </Label>
                                                        <div className="test-wrap large">
                                                            <Input
                                                                type="text"
                                                                name="your_name"
                                                                value={
                                                                    values.your_name
                                                                }
                                                                onChange={
                                                                    handleChange
                                                                }
                                                                onBlur={
                                                                    handleBlur
                                                                }
                                                                invalid={Boolean(
                                                                    touched.your_name &&
                                                                        errors.your_name
                                                                )}
                                                            />
                                                            <div className="form-bottom"></div>
                                                        </div>
                                                        <p className="error">
                                                            {errors.your_name}
                                                        </p>
                                                    </div>
                                                </FormGroup>
                                                <FormGroup className="form-section">
                                                    <div className="form-section-wrap">
                                                        <Label className="item-name">
                                                            返信先のメールアドレスを入力してください
                                                            <span className="required">
                                                                必須
                                                            </span>
                                                        </Label>
                                                        <div className="test-wrap large">
                                                            <Input
                                                                type="email"
                                                                name="email"
                                                                value={
                                                                    values.email
                                                                }
                                                                onChange={
                                                                    handleChange
                                                                }
                                                                onBlur={
                                                                    handleBlur
                                                                }
                                                                invalid={Boolean(
                                                                    touched.email &&
                                                                        errors.email
                                                                )}
                                                            />
                                                            <div className="form-bottom"></div>
                                                        </div>
                                                        <p className="error">
                                                            {errors.email}
                                                        </p>
                                                    </div>
                                                </FormGroup>
                                                <FormGroup className="form-section">
                                                    <div className="form-section-wrap">
                                                        <Label className="item-name">
                                                            性別を教えて下さい
                                                            <span className="required">
                                                                必須
                                                            </span>
                                                        </Label>
                                                        {consts.gender &&
                                                            (consts.gender
                                                                .data as KeyValue[]).map(
                                                                (e, index) => (
                                                                    <div
                                                                        className="radio-wrap"
                                                                        key={
                                                                            index
                                                                        }
                                                                    >
                                                                        <Label>
                                                                            <Input
                                                                                type="radio"
                                                                                name="gender"
                                                                                value={
                                                                                    e.key
                                                                                }
                                                                                checked={
                                                                                    values.gender ===
                                                                                    e.key +
                                                                                        ""
                                                                                        ? true
                                                                                        : false
                                                                                }
                                                                                onChange={
                                                                                    handleChange
                                                                                }
                                                                                onBlur={
                                                                                    handleBlur
                                                                                }
                                                                                invalid={Boolean(
                                                                                    touched.gender &&
                                                                                        errors.gender
                                                                                )}
                                                                            />{" "}
                                                                            <span>
                                                                                {
                                                                                    e.value
                                                                                }
                                                                            </span>
                                                                        </Label>
                                                                    </div>
                                                                )
                                                            )}
                                                        <p className="error">
                                                            {errors.gender}
                                                        </p>
                                                    </div>
                                                </FormGroup>
                                                <FormGroup className="form-section">
                                                    <div className="form-section-wrap">
                                                        <Label className="item-name">
                                                            年齢を教えて下さい
                                                            <span className="required">
                                                                必須
                                                            </span>
                                                        </Label>
                                                        <div className="select-wrap">
                                                            <Input
                                                                type="select"
                                                                name="age"
                                                                value={
                                                                    values.age
                                                                }
                                                                onChange={
                                                                    handleChange
                                                                }
                                                                onBlur={
                                                                    handleBlur
                                                                }
                                                                invalid={Boolean(
                                                                    touched.age &&
                                                                        errors.age
                                                                )}
                                                            >
                                                                <option value="">
                                                                    選択してください
                                                                </option>
                                                                {consts.age &&
                                                                    (consts.age
                                                                        .data as KeyValue[]).map(
                                                                        (
                                                                            e,
                                                                            index
                                                                        ) => (
                                                                            <option
                                                                                value={
                                                                                    e.key
                                                                                }
                                                                                key={
                                                                                    index
                                                                                }
                                                                            >
                                                                                {
                                                                                    e.value
                                                                                }
                                                                            </option>
                                                                        )
                                                                    )}
                                                            </Input>
                                                        </div>
                                                        <p className="error">
                                                            {errors.age}
                                                        </p>
                                                    </div>
                                                </FormGroup>
                                                <FormGroup className="form-section">
                                                    <div className="form-section-wrap">
                                                        <Label className="item-name">
                                                            件名を入力してください
                                                            <span className="required">
                                                                必須
                                                            </span>
                                                        </Label>
                                                        <div className="test-wrap large">
                                                            <Input
                                                                type="text"
                                                                name="title"
                                                                value={
                                                                    values.title
                                                                }
                                                                onChange={
                                                                    handleChange
                                                                }
                                                                onBlur={
                                                                    handleBlur
                                                                }
                                                                invalid={Boolean(
                                                                    touched.title &&
                                                                        errors.title
                                                                )}
                                                            />
                                                            <div className="form-bottom"></div>
                                                        </div>
                                                        <p className="error">
                                                            {errors.title}
                                                        </p>
                                                    </div>
                                                </FormGroup>
                                                <FormGroup className="form-section">
                                                    <div className="form-section-wrap">
                                                        <Label className="item-name">
                                                            お問い合わせ内容
                                                            <span className="required">
                                                                必須
                                                            </span>
                                                        </Label>
                                                        <div className="textarea-wrap large">
                                                            <Input
                                                                type="textarea"
                                                                name="contact"
                                                                value={
                                                                    values.contact
                                                                }
                                                                onChange={
                                                                    handleChange
                                                                }
                                                                onBlur={
                                                                    handleBlur
                                                                }
                                                                invalid={Boolean(
                                                                    touched.contact &&
                                                                        errors.contact
                                                                )}
                                                            />
                                                            <div className="form-bottom"></div>
                                                        </div>
                                                        <p className="error">
                                                            {errors.contact}
                                                        </p>
                                                    </div>
                                                </FormGroup>
                                                <FormGroup className="form-section">
                                                    <div className="form-section-wrap">
                                                        <Label className="item-name">
                                                            ホームページURLを入力してください
                                                            <span>任意</span>
                                                        </Label>
                                                        <div className="test-wrap large">
                                                            <Input
                                                                type="url"
                                                                name="url"
                                                                value={
                                                                    values.url
                                                                }
                                                                onChange={
                                                                    handleChange
                                                                }
                                                                onBlur={
                                                                    handleBlur
                                                                }
                                                                invalid={Boolean(
                                                                    touched.url &&
                                                                        errors.url
                                                                )}
                                                            />
                                                            <div className="form-bottom"></div>
                                                        </div>
                                                        <p className="error">
                                                            {errors.url}
                                                        </p>
                                                    </div>
                                                </FormGroup>
                                                <FormGroup className="form-section">
                                                    <div className="form-section-wrap">
                                                        <Label className="item-name">
                                                            画像を選択してください
                                                            <span>任意</span>
                                                        </Label>
                                                        <ImageFileInput
                                                            label="画像"
                                                            name="imageBase64"
                                                        />
                                                    </div>
                                                </FormGroup>
                                                <FormGroup className="form-section">
                                                    <div className="form-section-wrap">
                                                        <div className="checkbox-wrap">
                                                            <label>
                                                                <Input
                                                                    type="checkbox"
                                                                    name="caution"
                                                                    value="1"
                                                                    onChange={
                                                                        handleChange
                                                                    }
                                                                    onBlur={
                                                                        handleBlur
                                                                    }
                                                                    invalid={Boolean(
                                                                        touched.caution &&
                                                                            errors.caution
                                                                    )}
                                                                />{" "}
                                                                <span>
                                                                    注意事項に同意する
                                                                </span>
                                                            </label>
                                                        </div>
                                                        <p className="error">
                                                            {errors.caution}
                                                        </p>
                                                    </div>
                                                </FormGroup>
                                                <div className="submit-wrap">
                                                    <input
                                                        className="submit-btn btn btn-info"
                                                        type="submit"
                                                        value="送信する"
                                                    />
                                                </div>
                                            </Form>
                                        )}
                                    </Formik>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </Layout>
    );
};

export default ContactCreate;
