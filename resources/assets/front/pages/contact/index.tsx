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
  const { contactType } = state.const;

  const handleSubmit = async (values: Contact) => {
    await service.contact.registContact(values);
    navigate(Url.CONTACT_COMPLETE);
  };

  const initialValues = {
    type: '',
    title: '',
    message: '',
    image_base_64: '',
    caution: [],
  };

  const validation = Yup.object().shape({
    type: Yup.number().required(t('validation:requiredSelect', { field: t('contact:form.type') })),
    title: Yup.string()
      .max(100, t('validation:maxLength', { field: t('contact:form.subject'), max: 100 }))
      .required(t('validation:required', { field: t('contact:form.subject') })),
    message: Yup.string()
      .max(500, t('validation:maxLength', { field: t('contact:form.message'), max: 500 }))
      .required(t('validation:required', { field: t('contact:form.message') })),
    url: Yup.string().url(t('validation:url')),
    caution: Yup.array().min(1, t('validation:cautionRequired')),
  });

  return (
    <BasicLayout title={t('contact:title')}>
      <div className="bg-white p-6 rounded-md shadow-md">
        <Formik initialValues={initialValues} onSubmit={handleSubmit} validationSchema={validation}>
          {({ isValid, handleChange, handleBlur, values, errors, setFieldValue }) => (
            <Form>
              <CSRFToken />
              <SelectBox
                label={t('contact:form.type')}
                identity="type"
                name="type"
                selectedValue={values.type}
                options={(() => {
                  if (!contactType) return [];
                  const items = contactType as KeyValue[];
                  return items.map(({ key, value }) => ({
                    label: value,
                    value: String(key),
                  }));
                })()}
                onChange={handleChange}
                error={errors.type as string}
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
                label={t('contact:form.message')}
                identity="message"
                name="message"
                value={values.message}
                onChange={handleChange}
                onBlur={handleBlur}
                required={true}
                error={errors.message as string}
                className="mb-5 md:w-100"
              />
              <ImageInput
                label={t('contact:form.image')}
                identity="image_base_64"
                name="image_base_64"
                value={values.image_base_64}
                required={false}
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
