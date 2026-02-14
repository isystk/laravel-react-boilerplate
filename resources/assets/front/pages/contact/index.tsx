import * as Yup from 'yup';
import { Formik, Form } from 'formik';
import CSRFToken from '@/components/atoms/CSRFToken';
import { KeyValue } from '@/states/const';
import { useNavigate } from 'react-router-dom';
import { Url } from '@/constants/url';
import BasicLayout from '@/components/templates/BasicLayout';
import useAppRoot from '@/states/useAppRoot';
import TextInput from '@/components/atoms/TextInput';
import SelectionInput from '@/components/atoms/SelectionInput';
import SelectBox from '@/components/atoms/SelectBox';
import TextArea from '@/components/atoms/TextArea';
import ImageInput from '@/components/atoms/ImageInput';
import noImage from '@/assets/images/no_image.png';
import { Contact } from '@/services/contact';

const ContactCreate = () => {
  const { state, service } = useAppRoot();
  const navigate = useNavigate();

  if (!state) return <></>;
  const auth = state.auth;
  const { age, gender } = state.const;

  const handleSubmit = async (values: Contact) => {
    // 入力したお問い合わせ内容を送信する。
    await service.contact.registContact(values);
    // 完了画面を表示する
    navigate(Url.CONTACT_COMPLETE);
  };

  const initialValues = {
    user_name: auth.name || '',
    email: auth.email || '',
    gender: '',
    age: '',
    title: '',
    contact: '',
    url: '',
    image_base_64: '',
    caution: [],
  };

  const validation = Yup.object().shape({
    user_name: Yup.string()
      .max(20, 'お名前は20文字以下を入れてください')
      .required('お名前を入力してください'),
    email: Yup.string()
      .email('メールアドレスを正しく入力してしてください')
      .max(255, 'メールアドレスは255文字以下を入れてください')
      .required('メールアドレスを入力してください'),
    gender: Yup.number().required('性別を選択してください'),
    age: Yup.number().required('年齢を選択してください'),
    title: Yup.string()
      .max(50, '件名は50文字以下を入れてください')
      .required('件名を入力してください'),
    contact: Yup.string()
      .max(200, 'お問い合わせ内容は200文字以下を入れてください')
      .required('お問い合わせ内容を入力してください'),
    url: Yup.string().url('URLを正しく入力してください'),
    image_base_64: Yup.string().required('画像を選択してください'),
    caution: Yup.array().min(1, '注意事項に同意してください'),
  });

  return (
    <BasicLayout title="お問い合わせ">
      <div className="bg-white p-6 rounded-md shadow-md">
        <Formik initialValues={initialValues} onSubmit={handleSubmit} validationSchema={validation}>
          {({ isValid, handleChange, handleBlur, values, errors, setFieldValue }) => (
            <Form>
              <CSRFToken />
              <TextInput
                label="お名前"
                controlType="text"
                identity="user_name"
                name="user_name"
                value={values.user_name}
                onChange={handleChange}
                onBlur={handleBlur}
                required={true}
                error={errors.user_name as string}
                className="mb-5 md:w-100"
              />
              <TextInput
                label="メールアドレス"
                controlType="email"
                identity="email"
                name="email"
                value={values.email}
                onChange={handleChange}
                onBlur={handleBlur}
                required={true}
                error={errors.email as string}
                className="mb-5 md:w-100"
              />
              <SelectionInput
                label="性別"
                identity="gender"
                name="gender"
                controlType="radio"
                selectedValue={values.gender}
                options={(() => {
                  if (!gender) return [];
                  const items = gender as KeyValue[];
                  return items.map(({ key, value }) => ({
                    label: value,
                    value: String(key),
                  }));
                })()}
                onChange={handleChange}
                required={true}
                error={errors.gender as string}
                className="mb-5 md:w-100"
              />
              <SelectBox
                label="年齢"
                identity="age"
                name="age"
                selectedValue={values.age}
                options={(() => {
                  if (!age) return [];
                  const items = age as KeyValue[];
                  return items.map(({ key, value }) => ({
                    label: value,
                    value: String(key),
                  }));
                })()}
                onChange={handleChange}
                error={errors.age as string}
                required={true}
                className="mb-5 md:w-100"
              />
              <TextInput
                label="件名"
                controlType="text"
                identity="title"
                name="title"
                value={values.title}
                onChange={handleChange}
                onBlur={handleBlur}
                required={true}
                error={errors.title as string}
                className="mb-5 md:w-100"
              />
              <TextArea
                label="お問い合わせ内容"
                identity="contact"
                name="contact"
                value={values.contact}
                onChange={handleChange}
                onBlur={handleBlur}
                required={true}
                error={errors.contact as string}
                className="mb-5 md:w-100"
              />
              <TextInput
                label="ホームページURL"
                controlType="url"
                identity="url"
                name="url"
                value={values.url}
                onChange={handleChange}
                onBlur={handleBlur}
                error={errors.url as string}
                className="mb-5 md:w-100"
              />
              <ImageInput
                label="画像"
                identity="image_base_64"
                name="image_base_64"
                value={values.image_base_64}
                required={true}
                setFieldValue={setFieldValue}
                error={errors.image_base_64 as string}
                className="mb-5 md:w-100"
                noImage={noImage}
              />
              <SelectionInput
                identity="caution"
                name="caution"
                controlType="checkbox"
                checkedValues={values.caution}
                options={[{ value: 'true', label: '注意事項に同意' }]}
                onChange={handleChange}
                required={true}
                error={errors.caution as string}
                className="mb-5 md:w-100"
              />
              <div className="text-center">
                <button type="submit" className="btn btn-primary" disabled={!isValid}>
                  送信する
                </button>
              </div>
            </Form>
          )}
        </Formik>
      </div>
    </BasicLayout>
  );
};

export default ContactCreate;
