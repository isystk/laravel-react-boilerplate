import * as React from 'react'

import { Form, FormGroup, Label, Input } from 'reactstrap'
import { Formik } from 'formik'
import * as Yup from 'yup'
import CSRFToken from '../../containers/Elements/CSRFToken'
import { API } from '../../utilities'
import { API_ENDPOINT } from '../../common/constants/api'
import { URL } from '../../common/constants/url'

import { Auth, Consts, KeyValue } from '../../store/StoreTypes'
import ReactImageBase64 from 'react-image-base64'

type State = {
  imageBase64?: string | null
  fileName?: string
  fileErrorMessage?: string
}
type Props = {
  auth: Auth
  consts: Consts
  push
}

export class ContactCreate extends React.Component<Props, State> {
  constructor(props) {
    super(props)
    this.state = {
      imageBase64: null,
      fileName: '',
    }
  }

  handleSubmit = async values => {
    // alert(JSON.stringify(values))

    // 入力したお問い合わせ内容を送信する。
    const response = await API.post(API_ENDPOINT.CONTACT_STORE, values)

    if (response.result) {
      // 完了画面を表示する
      this.props.push(URL.CONTACT_COMPLETE)
    }
  }

  submit: any

  render(): JSX.Element {
    return (
      <div className="container">
        <div className="row justify-content-center">
          <div className="col-md-8">
            <div className="card">
              <div className="card-header">お問い合わせ登録</div>

              <div className="card-body">
                <Formik
                  initialValues={{
                    your_name: this.props.auth.name || '',
                    email: this.props.auth.email || '',
                    gender: '',
                    age: '',
                    title: '',
                    contact: '',
                    url: '',
                    caution: [],
                    imageBase64: '',
                    fileName: '',
                  }}
                  onSubmit={values => this.handleSubmit(values)}
                  validationSchema={Yup.object().shape({
                    your_name: Yup.string()
                      .max(20, 'お名前は20文字以下を入れてください')
                      .required('お名前を入力してください'),
                    email: Yup.string()
                      .email('メールアドレスを正しく入力してしてください')
                      .max(255, 'メールアドレスは255文字以下を入れてください')
                      .required('メールアドレスを入力してください'),
                    gender: Yup.number().required('性別を選択してください'),
                    age: Yup.number().required('年齢を選択してください'),
                    title: Yup.string()
                      .max(50, 'タイトルは50文字以下を入れてください')
                      .required('タイトルを入力してください'),
                    contact: Yup.string()
                      .max(200, 'タイトルは200文字以下を入れてください')
                      .required('本文を入力してください'),
                    url: Yup.string()
                      // .matches(
                      //   /((https?):\/\/)?(www.)?[a-z0-9]+(\.[a-z]{2,}){1,3}(#?\/?[a-zA-Z0-9#]+)*\/?(\?[a-zA-Z0-9-_]+=[a-zA-Z0-9-%]+&?)?$/,
                      //   'URLを正しく入力してください',
                      // ),
                      .url('URLを正しく入力してください'),
                    caution: Yup.array().min(1, '注意事項に同意してください'),
                  })}
                >
                  {({ handleSubmit, handleChange, handleBlur, values, errors, touched }) => (
                    <Form onSubmit={handleSubmit}>
                      <CSRFToken />

                      <FormGroup className="form-section">
                        <div className="form-section-wrap">
                          <Label className="item-name">
                            お名前を入力してください<span className="required">必須</span>
                          </Label>
                          <div className="test-wrap large">
                            <Input
                              type="text"
                              name="your_name"
                              value={values.your_name}
                              onChange={handleChange}
                              onBlur={handleBlur}
                              invalid={Boolean(touched.your_name && errors.your_name)}
                            />
                            <div className="form-bottom"></div>
                          </div>
                          <p className="error">{errors.your_name}</p>
                        </div>
                      </FormGroup>
                      <FormGroup className="form-section">
                        <div className="form-section-wrap">
                          <Label className="item-name">
                            返信先のメールアドレスを入力してください<span className="required">必須</span>
                          </Label>
                          <div className="test-wrap large">
                            <Input
                              type="email"
                              name="email"
                              value={values.email}
                              onChange={handleChange}
                              onBlur={handleBlur}
                              invalid={Boolean(touched.email && errors.email)}
                            />
                            <div className="form-bottom"></div>
                          </div>
                          <p className="error">{errors.email}</p>
                        </div>
                      </FormGroup>
                      <FormGroup className="form-section">
                        <div className="form-section-wrap">
                          <Label className="item-name">
                            性別を教えて下さい<span className="required">必須</span>
                          </Label>
                          {this.props.consts.gender &&
                            (this.props.consts.gender.data as KeyValue[]).map((e, index) => (
                              <div className="radio-wrap" key={index}>
                                <Label>
                                  <Input
                                    type="radio"
                                    name="gender"
                                    value={e.key}
                                    checked={values.gender === e.key + '' ? true : false}
                                    onChange={handleChange}
                                    onBlur={handleBlur}
                                    invalid={Boolean(touched.gender && errors.gender)}
                                  />{' '}
                                  <span>{e.value}</span>
                                </Label>
                              </div>
                            ))}
                          <p className="error">{errors.gender}</p>
                        </div>
                      </FormGroup>
                      <FormGroup className="form-section">
                        <div className="form-section-wrap">
                          <Label className="item-name">
                            年齢を教えて下さい<span className="required">必須</span>
                          </Label>
                          <div className="select-wrap">
                            <Input
                              type="select"
                              name="age"
                              value={values.age}
                              onChange={handleChange}
                              onBlur={handleBlur}
                              invalid={Boolean(touched.age && errors.age)}
                            >
                              <option value="">選択してください</option>
                              {this.props.consts.age &&
                                (this.props.consts.age.data as KeyValue[]).map((e, index) => (
                                  <option value={e.key} key={index}>
                                    {e.value}
                                  </option>
                                ))}
                            </Input>
                          </div>
                          <p className="error">{errors.age}</p>
                        </div>
                      </FormGroup>
                      <FormGroup className="form-section">
                        <div className="form-section-wrap">
                          <Label className="item-name">
                            件名を入力してください<span className="required">必須</span>
                          </Label>
                          <div className="test-wrap large">
                            <Input
                              type="text"
                              name="title"
                              value={values.title}
                              onChange={handleChange}
                              onBlur={handleBlur}
                              invalid={Boolean(touched.title && errors.title)}
                            />
                            <div className="form-bottom"></div>
                          </div>
                          <p className="error">{errors.title}</p>
                        </div>
                      </FormGroup>
                      <FormGroup className="form-section">
                        <div className="form-section-wrap">
                          <Label className="item-name">
                            お問い合わせ内容<span className="required">必須</span>
                          </Label>
                          <div className="textarea-wrap large">
                            <Input
                              type="textarea"
                              name="contact"
                              value={values.contact}
                              onChange={handleChange}
                              onBlur={handleBlur}
                              invalid={Boolean(touched.contact && errors.contact)}
                            />
                            <div className="form-bottom"></div>
                          </div>
                          <p className="error">{errors.contact}</p>
                        </div>
                      </FormGroup>
                      <FormGroup className="form-section">
                        <div className="form-section-wrap">
                          <Label className="item-name">
                            ホームページURLを入力してください<span>任意</span>
                          </Label>
                          <div className="test-wrap large">
                            <Input
                              type="url"
                              name="url"
                              value={values.url}
                              onChange={handleChange}
                              onBlur={handleBlur}
                              invalid={Boolean(touched.url && errors.url)}
                            />
                            <div className="form-bottom"></div>
                          </div>
                          <p className="error">{errors.url}</p>
                        </div>
                      </FormGroup>
                      <FormGroup className="form-section">
                        <div className="form-section-wrap">
                          <Label className="item-name">
                            画像を選択してください<span>任意</span>
                          </Label>
                          <div className="textarea-wrap" id="drop-zone">
                            <ReactImageBase64
                              maxFileSize={10485760}
                              thumbnail_size={500}
                              drop={true}
                              dropText="ファイルをドラッグ＆ドロップもしくは"
                              handleChange={data => {
                                this.setState({
                                  imageBase64: data.fileData,
                                  fileName: data.fileName,
                                })
                              }}
                            />
                            <div id="result">
                              {this.state.imageBase64 &&
                                (() => {
                                  values.imageBase64 = this.state.imageBase64
                                  values.fileName = this.state.fileName + ''
                                  return <img src={this.state.imageBase64} width="200px" />
                                })()}
                              <Input
                                type="hidden"
                                name="imageBase64"
                                value={values.imageBase64}
                                onChange={handleChange}
                              />
                              <Input type="hidden" name="fileName" value={values.fileName} onChange={handleChange} />
                            </div>
                          </div>
                          <p className="error">{this.state.fileErrorMessage}</p>
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
                                onChange={handleChange}
                                onBlur={handleBlur}
                                invalid={Boolean(touched.caution && errors.caution)}
                              />{' '}
                              <span>注意事項に同意する</span>
                            </label>
                          </div>
                          <p className="error">{errors.caution}</p>
                        </div>
                      </FormGroup>
                      <div className="submit-wrap">
                        <input
                          className="submit-btn btn btn-info"
                          type="submit"
                          value="送信する"
                          onClick={this.submit}
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
    )
  }
}

export default ContactCreate
