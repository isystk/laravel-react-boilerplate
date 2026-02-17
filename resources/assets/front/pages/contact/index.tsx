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
import { useTranslation } from 'react-i18next';

const ContactCreate = () => {
  const { state, service } = useAppRoot();
  const navigate = useNavigate();
  const { t } = useTranslation(['contact', 'validation']);

  if (!state) return <></>;
  const auth = state.auth;
  const { age, gender } = state.const;

  const handleSubmit = async (values: Contact) => {
    await service.contact.registContact(values);
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
      .max(20, t('validation:maxLength', { field: t('contact:form.name'), max: 20 }))
      .required(t('validation:required', { field: t('contact:form.name') })),
    email: Yup.string()
      .email(t('validation:email'))
      .max(255, t('validation:maxLength', { field: t('contact:form.email'), max: 255 }))
      .required(t('validation:required', { field: t('contact:form.email') })),
    gender: Yup.number().required(t('validation:requiredSelect', { field: t('contact:form.gender') })),
    age: Yup.number().required(t('validation:requiredSelect', { field: t('contact:form.age') })),
    title: Yup.string()
      .max(50, t('validation:maxLength', { field: t('contact:form.subject'), max: 50 }))
      .required(t('validation:required', { field: t('contact:form.subject') })),
    contact: Yup.string()
      .max(200, t('validation:maxLength', { field: t('contact:form.content'), max: 200 }))
      .required(t('validation:required', { field: t('contact:form.content') })),
    url: Yup.string().url(t('validation:url')),
    image_base_64: Yup.string().required(t('validation:imageRequired')),
    caution: Yup.array().min(1, t('validation:cautionRequired')),
  });

  return (
    <BasicLayout title={t('contact:title')}>
      <div className="bg-white p-6 rounded-md shadow-md">
        <Formik initialValues={initialValues} onSubmit={handleSubmit} validationSchema={validation}>
          {({ isValid, handleChange, handleBlur, values, errors, setFieldValue }) => (
            <Form>
              <CSRFToken />
              <TextInput
                label={t('contact:form.name')}
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
                label={t('contact:form.email')}
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
                label={t('contact:form.gender')}
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
                label={t('contact:form.age')}
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
                label={t('contact:form.subject')}
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
                label={t('contact:form.content')}
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
                label={t('contact:form.url')}
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
                label={t('contact:form.image')}
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
                options={[{ value: 'true', label: t('contact:form.caution') }]}
                onChange={handleChange}
                required={true}
                error={errors.caution as string}
                className="mb-5 md:w-100"
              />
              <div className="text-center">
                <button type="submit" className="btn btn-primary" disabled={!isValid}>
                  {t('contact:form.submit')}
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
