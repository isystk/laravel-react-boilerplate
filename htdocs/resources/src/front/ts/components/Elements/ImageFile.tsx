import React, { useState, FC } from "react";
import { Field } from "formik";
import ReactImageBase64 from "react-image-base64";
import { CardImg } from "reactstrap";

type Props = {
    label: string;
    name: string;
};

export const ImageFileInput: FC<Props> = ({ label, name, ...rest }) => {
    const [photoErrors, setPhotoErrors] = useState<string[]>([]);

    return (
        <div className="form-control">
            <p>{label}</p>
            <div>
                <div>
                    <Field name={name} {...rest}>
                        {// @ts-ignore
                        ({ form }) => {
                            const { setFieldValue, errors } = form;
                            return (
                                <>
                                    <ReactImageBase64
                                        maxFileSize={10485760}
                                        thumbnail_size={100}
                                        drop={true}
                                        dropText="写真をドラッグ＆ドロップもしくは"
                                        capture="environment"
                                        multiple={false}
                                        handleChange={data => {
                                            if (data.result) {
                                                setFieldValue(
                                                    name,
                                                    data.fileData
                                                );
                                            } else {
                                                setPhotoErrors([
                                                    ...photoErrors,
                                                    ...data.messages
                                                ]);
                                            }
                                        }}
                                    />
                                    <p className="error">{errors.photo}</p>
                                    {photoErrors.map((error, index) => (
                                        <p className="error" key={index}>
                                            {error}
                                        </p>
                                    ))}
                                </>
                            );
                        }}
                    </Field>
                </div>
                <div>
                    <div name={name} {...rest}>
                        {// @ts-ignore
                        ({ form }) => {
                            const { values } = form;
                            console.log(values);
                            return (
                                <CardImg
                                    src={
                                        values[name] ||
                                        "/assets/front/image/no_image.png"
                                    }
                                    alt="Contemplative Reptile"
                                />
                            );
                        }}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default ImageFileInput;
