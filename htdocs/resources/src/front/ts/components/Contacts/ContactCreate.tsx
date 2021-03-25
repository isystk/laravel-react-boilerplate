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
    this.handleFileChange = this.handleFileChange.bind(this)
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

  handleFileChange = e => {
    const maxFileSize = 10485760 // アップロード可能な最大ファイルサイズ 10BM
    const thumbnail_width = 500 // 画像リサイズ後の横の長さの最大値
    const thumbnail_height = 500 // 画像リサイズ後の縦の長さの最大値

    // 入力チェック
    const validate = blob => {
      const errors: string[] = []
      // ファイルサイズチェック
      if (maxFileSize < blob.size) {
        errors.push('画像ファイルのファイルサイズが最大値(' + Math.floor(maxFileSize / 1000000) + 'MB)を超えています。')
      }
      return errors
    }

    const errorCallback = values => {
      this.setState({ fileErrorMessage: values[0] })
    }

    const successCallback = values => {
      this.setState({
        fileName: values.fileName,
        imageBase64: values.fileData,
      })
    }

    // 画像のリサイズ
    const resize = function(blob, callback, errorCallback) {
      const image = new Image()
      const fr = new FileReader()
      fr.onload = function(evt) {
        // リサイズする
        image.onload = function() {
          let width, height
          if (image.width > image.height) {
            // 横長の画像は横のサイズを指定値にあわせる
            const ratio = image.height / image.width
            width = thumbnail_width
            height = thumbnail_width * ratio
          } else {
            // 縦長の画像は縦のサイズを指定値にあわせる
            const ratio = image.width / image.height
            width = thumbnail_height * ratio
            height = thumbnail_height
          }
          // サムネ描画用canvasのサイズを上で算出した値に変更
          const canvas = document.createElement('canvas')
          canvas.id = 'canvas'
          canvas.width = width
          canvas.height = height
          const ctx = canvas.getContext('2d')
          if (ctx) {
            // canvasに既に描画されている画像をクリア
            ctx.clearRect(0, 0, width, height)
            // canvasにサムネイルを描画
            ctx.drawImage(image, 0, 0, image.width, image.height, 0, 0, width, height)
          }

          // canvasからbase64画像データを取得
          const base64 = canvas.toDataURL('image/jpeg')
          // base64からBlobデータを作成
          const bin = atob(base64.split('base64,')[1])
          const len = bin.length
          const barr = new Uint8Array(len)
          let i = 0
          while (i < len) {
            barr[i] = bin.charCodeAt(i)
            i++
          }
          const resizeBlob = new Blob([barr], { type: 'image/jpeg' })
          callback({
            fileName: blob.name,
            ofileData: evt.target ? evt.target.result : null,
            fileData: base64,
            ofileSize: blob.size,
            fileSize: resizeBlob.size,
            fileType: resizeBlob.type,
          })
        }
        image.onerror = function() {
          errorCallback(['選択されたファイルをロードできません。'])
        }
        image.src = evt.target ? evt.target.result + '' : ''
      }
      fr.readAsDataURL(blob)
    }

    _.each(e.target.files, (file, index) => {
      console.log(index + ': ' + file)

      function getExt(filename) {
        const pos = filename.lastIndexOf('.')
        if (pos === -1) return ''
        return filename.slice(pos + 1)
      }
      const ext = getExt(file.name).toLowerCase()

      if (ext === 'heic') {
        // HEIC対応 iphone11 以降で撮影された画像にも対応する
        // console.log('HEIC形式の画像なのでJPEGに変換します。')

        window
          .heic2any({
            blob: file,
            toType: 'image/jpeg',
            quality: 1,
          })
          .then(function(resultBlob) {
            const errors = validate(resultBlob)
            if (0 < errors.length) {
              errorCallback(errors)
              return
            }
            resize(
              resultBlob,
              function(res) {
                res.fileName = file.name
                successCallback(res)
              },
              function(errors) {
                errorCallback(errors)
                return
              },
            )
          })
      } else {
        const errors = validate(file)
        if (0 < errors.length) {
          errorCallback(errors)
          return
        }
        resize(
          file,
          function(res) {
            successCallback(res)
          },
          function(errors) {
            errorCallback(errors)
            return
          },
        )
      }
    })
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
                            <p>
                              <Input
                                type="file"
                                id="js-uploadImage"
                                // multiple="multiple"
                                accept="image/*"
                                capture="environment"
                                onChange={this.handleFileChange}
                              />
                            </p>
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
