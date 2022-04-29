import React, { useState, FC } from "react";
import { Field } from "formik";
import ReactImageBase64 from "react-image-base64";
import { CardImg, Col, Container, Row } from "reactstrap";

type Props = {
    label: string;
    name: string;
};

export const ImageFileInput: FC<Props> = ({ label, name, ...rest }) => {
    const [photoErrors, setPhotoErrors] = useState<string[]>([]);

    return (
        <Container>
            <Row>
                <Col md="6">
                    <Field name={name} {...rest}>
                        {
                            // @ts-ignore
                            ({ form }) => {
                                const { setFieldValue, errors } = form;
                                return (
                                    <>
                                        <ReactImageBase64
                                            maxFileSize={10485760}
                                            thumbnail_size={800}
                                            drop={true}
                                            dropText="写真をドラッグ＆ドロップもしくは"
                                            capture="environment"
                                            multiple={false}
                                            handleChange={(data) => {
                                                if (data.result) {
                                                    setFieldValue(
                                                        name,
                                                        data.fileData
                                                    );
                                                } else {
                                                    setPhotoErrors([
                                                        ...photoErrors,
                                                        ...data.messages,
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
                            }
                        }
                    </Field>
                </Col>
                <Col md="6">
                    <Field name={name} {...rest}>
                        {
                            // @ts-ignore
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
                            }
                        }
                    </Field>
                </Col>
            </Row>
        </Container>
    );
};

export default ImageFileInput;
