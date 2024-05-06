import * as React from "react";
import * as Yup from "yup";
import { FC } from "react";
import { FormGroup, Label, Input, Button } from "reactstrap";
import { Formik, Form } from "formik";
import CSRFToken from "@/components/elements/CSRFToken";
import Layout from "@/components/Layout";
import MainService from "@/services/main";
import { KeyValue } from "@/services/const";
import ImageFileInput from "@/components/elements/ImageFile";
import Box from "@/components/Box";
import { useNavigate } from "react-router-dom";
import { Url } from "@/constants/url";

type Props = {
    appRoot: MainService;
};

const ContactCreate: FC<Props> = ({ appRoot }) => {
    const navigate = useNavigate();
    const auth = appRoot.auth;
    const consts = appRoot.const.data;

    const handleSubmit = async (values) => {
        // 入力したお問い合わせ内容を送信する。
        const result = await appRoot.contact.registContact(values);
        if (result) {
            // 完了画面を表示する
            navigate(Url.CONTACT_COMPLETE);
        }
    };

    const initialValues = {
        user_name: auth.name || "",
        email: auth.email || "",
        gender: "",
        age: "",
        title: "",
        contact: "",
        url: "",
        imageBase64_1: "",
        imageBase64_2: "",
        imageBase64_3: "",
        caution: [],
    };

    const validation = Yup.object().shape({
        user_name: Yup.string()
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
        caution: Yup.array().min(1, "注意事項に同意してください"),
    });

    return (
        <Layout appRoot={appRoot} title="お問い合わせ">
            <main className="main">
                <Box title="お問い合わせ">
                    <Formik
                        initialValues={initialValues}
                        onSubmit={handleSubmit}
                        validationSchema={validation}
                    >
                        {({
                            isValid,
                            handleChange,
                            handleBlur,
                            values,
                            errors,
                            touched,
                        }) => (
                            <Form>
                                <CSRFToken appRoot={appRoot} />
                                <FormGroup>
                                    <div style={{ width: "100%" }}>
                                        <Label
                                            for="user_name"
                                            className="item-name"
                                        >
                                            お名前を入力してください
                                            <span className="required">
                                                必須
                                            </span>
                                        </Label>
                                        <div className="text-wrap large">
                                            <Input
                                                type="text"
                                                id="user_name"
                                                name="user_name"
                                                value={values.user_name}
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                invalid={Boolean(
                                                    touched.user_name &&
                                                        errors.user_name
                                                )}
                                            />
                                            <div className="form-bottom"></div>
                                            <p className="error">
                                                {errors.user_name}
                                            </p>
                                        </div>
                                    </div>
                                </FormGroup>
                                <FormGroup>
                                    <div style={{ width: "100%" }}>
                                        <Label className="item-name">
                                            返信先のメールアドレスを入力してください
                                            <span className="required">
                                                必須
                                            </span>
                                        </Label>
                                        <div className="text-wrap large">
                                            <Input
                                                type="email"
                                                name="email"
                                                value={values.email}
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                invalid={Boolean(
                                                    touched.email &&
                                                        errors.email
                                                )}
                                            />
                                            <div className="form-bottom"></div>
                                        </div>
                                        <p className="error">{errors.email}</p>
                                    </div>
                                </FormGroup>
                                <FormGroup>
                                    <div style={{ width: "100%" }}>
                                        <Label className="item-name">
                                            性別を教えて下さい
                                            <span className="required">
                                                必須
                                            </span>
                                        </Label>
                                        {consts.gender &&
                                            (
                                                consts.gender.data as KeyValue[]
                                            ).map((e, index) => (
                                                <div
                                                    className="radio-wrap"
                                                    key={index}
                                                >
                                                    <Label>
                                                        <Input
                                                            type="radio"
                                                            name="gender"
                                                            value={e.key}
                                                            checked={
                                                                values.gender ===
                                                                e.key + ""
                                                                    ? true
                                                                    : false
                                                            }
                                                            onChange={
                                                                handleChange
                                                            }
                                                            onBlur={handleBlur}
                                                            invalid={Boolean(
                                                                touched.gender &&
                                                                    errors.gender
                                                            )}
                                                        />{" "}
                                                        <span>{e.value}</span>
                                                    </Label>
                                                </div>
                                            ))}
                                        <p className="error">{errors.gender}</p>
                                    </div>
                                </FormGroup>
                                <FormGroup>
                                    <div style={{ width: "100%" }}>
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
                                                value={values.age}
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                invalid={Boolean(
                                                    touched.age && errors.age
                                                )}
                                            >
                                                <option value="">
                                                    選択してください
                                                </option>
                                                {consts.age &&
                                                    (
                                                        consts.age
                                                            .data as KeyValue[]
                                                    ).map((e, index) => (
                                                        <option
                                                            value={e.key}
                                                            key={index}
                                                        >
                                                            {e.value}
                                                        </option>
                                                    ))}
                                            </Input>
                                        </div>
                                        <p className="error">{errors.age}</p>
                                    </div>
                                </FormGroup>
                                <FormGroup>
                                    <div style={{ width: "100%" }}>
                                        <Label className="item-name">
                                            件名を入力してください
                                            <span className="required">
                                                必須
                                            </span>
                                        </Label>
                                        <div className="text-wrap large">
                                            <Input
                                                type="text"
                                                name="title"
                                                value={values.title}
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                invalid={Boolean(
                                                    touched.title &&
                                                        errors.title
                                                )}
                                            />
                                            <div className="form-bottom"></div>
                                        </div>
                                        <p className="error">{errors.title}</p>
                                    </div>
                                </FormGroup>
                                <FormGroup>
                                    <div style={{ width: "100%" }}>
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
                                                value={values.contact}
                                                onChange={handleChange}
                                                onBlur={handleBlur}
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
                                <FormGroup>
                                    <div style={{ width: "100%" }}>
                                        <Label className="item-name">
                                            ホームページURLを入力してください
                                            <span>任意</span>
                                        </Label>
                                        <div className="text-wrap large">
                                            <Input
                                                type="url"
                                                name="url"
                                                value={values.url}
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                invalid={Boolean(
                                                    touched.url && errors.url
                                                )}
                                            />
                                            <div className="form-bottom"></div>
                                        </div>
                                        <p className="error">{errors.url}</p>
                                    </div>
                                </FormGroup>
                                <FormGroup>
                                    <div style={{ width: "100%" }}>
                                        <Label className="item-name">
                                            画像1を選択してください
                                            <span>任意</span>
                                        </Label>
                                        <ImageFileInput
                                            label="画像1"
                                            name="imageBase64_1"
                                        />
                                    </div>
                                </FormGroup>
                                <FormGroup>
                                    <div style={{ width: "100%" }}>
                                        <Label className="item-name">
                                            画像2を選択してください
                                            <span>任意</span>
                                        </Label>
                                        <ImageFileInput
                                            label="画像2"
                                            name="imageBase64_2"
                                        />
                                    </div>
                                </FormGroup>
                                <FormGroup>
                                    <div style={{ width: "100%" }}>
                                        <Label className="item-name">
                                            画像3を選択してください
                                            <span>任意</span>
                                        </Label>
                                        <ImageFileInput
                                            label="画像3"
                                            name="imageBase64_3"
                                        />
                                    </div>
                                </FormGroup>
                                <FormGroup>
                                    <div style={{ width: "100%" }}>
                                        <div className="checkbox-wrap">
                                            <label>
                                                <Input
                                                    type="checkbox"
                                                    name="caution"
                                                    value="1"
                                                    onChange={handleChange}
                                                    onBlur={handleBlur}
                                                    invalid={Boolean(
                                                        touched.caution &&
                                                            errors.caution
                                                    )}
                                                />{" "}
                                                <span>注意事項に同意する</span>
                                            </label>
                                        </div>
                                        <p className="error">
                                            {errors.caution}
                                        </p>
                                    </div>
                                </FormGroup>
                                <div
                                    className="submit-wrap"
                                    style={{ width: "300px", margin: "auto" }}
                                >
                                    <Button
                                        type="submit"
                                        color="primary"
                                        block
                                        disabled={!isValid}
                                        fullWidth
                                    >
                                        送信する
                                    </Button>
                                </div>
                            </Form>
                        )}
                    </Formik>
                </Box>
            </main>
        </Layout>
    );
};

export default ContactCreate;
