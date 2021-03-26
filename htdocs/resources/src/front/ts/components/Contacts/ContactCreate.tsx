import * as React from 'react'
import { connect } from 'react-redux'
import * as _ from 'lodash'
import { push } from 'connected-react-router'

import { Form, FormGroup, Label, Input, FormFeedback } from 'reactstrap'
import { Formik } from 'formik'
import * as Yup from 'yup'
import CSRFToken from '../Elements/CSRFToken'
import { API } from '../../utilities'
import { API_ENDPOINT } from '../../common/constants/api'
import { URL } from '../../common/constants/url'

import ReactImageBase64 from 'react-image-base64'

interface IState {
  imageBase64?: string
  fileName?: string
  fileErrorMessage?: string
}
interface IProps {
  push
}

export class ContactCreate extends React.Component<IProps, IState> {
  constructor(props) {
    super(props)
    this.state = {
      fileName: '',
      imageBase64: '',
    }
  }

  handleSubmit = async values => {
    // alert(JSON.stringify(values))

    // 入力したお問い合わせ内容を送信する。
    const response = await API.post(API_ENDPOINT.CONTACT_STORE, values)

    if (response.result) {
      alert('送信完了')

      // トップ画面に戻る
      this.props.push(URL.TOP)
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
                    your_name: '1',
                    email: '2@gmail.com',
                    gender: 1,
                    age: 4,
                    title: 'a',
                    contact: 'bb',
                    url: 'http://localhost',
                    caution: 1,
                    imageBase64: '',
                    fileName: '',
                  }}
                  onSubmit={values => this.handleSubmit(values)}
                  validationSchema={Yup.object().shape({})}
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
                          <FormFeedback>{errors.your_name}</FormFeedback>
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
                          <FormFeedback>{errors.email}</FormFeedback>
                        </div>
                      </FormGroup>
                      <FormGroup className="form-section">
                        <div className="form-section-wrap">
                          <Label className="item-name">
                            性別を教えて下さい<span className="required">必須</span>
                          </Label>
                          <div className="radio-wrap">
                            <Label>
                              <Input
                                type="radio"
                                name="gender"
                                value="0"
                                checked={values.gender === 0 ? true : false}
                                onChange={handleChange}
                                onBlur={handleBlur}
                                invalid={Boolean(touched.gender && errors.gender)}
                              />{' '}
                              <span>女性</span>
                            </Label>
                          </div>
                          <div className="radio-wrap">
                            <Label>
                              <Input
                                type="radio"
                                name="gender"
                                value="1"
                                checked={values.gender === 1 ? true : false}
                                onChange={handleChange}
                                onBlur={handleBlur}
                                invalid={Boolean(touched.gender && errors.gender)}
                              />{' '}
                              <span>男性</span>
                            </Label>
                          </div>
                        </div>
                        <FormFeedback>{errors.gender}</FormFeedback>
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
                              <option value="1">～19歳</option>
                              <option value="2">20歳～29歳</option>
                              <option value="3">30歳～39歳</option>
                              <option value="4">40歳～49歳</option>
                              <option value="5">50歳～59歳</option>
                              <option value="6">60歳～</option>
                            </Input>
                          </div>
                        </div>
                        <FormFeedback>{errors.age}</FormFeedback>
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
                          <FormFeedback>{errors.title}</FormFeedback>
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
                          <FormFeedback>{errors.contact}</FormFeedback>
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
                          <FormFeedback>{errors.url}</FormFeedback>
                        </div>
                      </FormGroup>
                      <FormGroup className="form-section">
                        <div className="form-section-wrap">
                          <Label className="item-name">
                            画像を選択してください<span>任意</span>
                          </Label>
                          <div className="textarea-wrap" id="drop-zone">
                            <ReactImageBase64
                              handleChange={data => {
                                console.log(data)
                                this.setState({
                                  imageBase64: data.imageBase64,
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
                          <FormFeedback>{this.state.fileErrorMessage}</FormFeedback>
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
                          <FormFeedback>{errors.caution}</FormFeedback>
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

const mapStateToProps = () => {
  return {}
}

const mapDispatchToProps = {
  push,
}

export default connect(mapStateToProps, mapDispatchToProps)(ContactCreate)
