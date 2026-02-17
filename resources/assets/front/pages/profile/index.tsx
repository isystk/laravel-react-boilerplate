import { useState, useRef, type ChangeEvent, useEffect } from 'react';
import CSRFToken from '@/components/atoms/CSRFToken';
import BasicLayout from '@/components/templates/BasicLayout';
import useAppRoot from '@/states/useAppRoot';
import TextInput from '@/components/atoms/TextInput';
import { useTranslation } from 'react-i18next';

const languages = [
  { code: 'ja', label: '日本語' },
  { code: 'en', label: 'English' },
  { code: 'zh', label: '中文' },
];

const ProfilePage = () => {
  const { state, service } = useAppRoot();
  const { t, i18n } = useTranslation('profile');
  const [name, setName] = useState<string>(state.auth.name || '');
  const [avatarPreview, setAvatarPreview] = useState<string | null>(state.auth.avatar_url || null);
  const [avatarBase64, setAvatarBase64] = useState<string | null>(null);
  const fileInputRef = useRef<HTMLInputElement>(null);

  useEffect(() => {
    setName(state.auth.name || '');
    setAvatarPreview(state.auth.avatar_url || null);
  }, [state.auth.name, state.auth.avatar_url]);

  const handleAvatarChange = (e: ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      const reader = new FileReader();
      reader.onloadend = () => {
        const result = reader.result as string;
        setAvatarPreview(result);
        setAvatarBase64(result);
      };
      reader.readAsDataURL(file);
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    await service.profile.updateProfile({
      name,
      avatar: avatarBase64,
    });
  };

  const handleLanguageChange = (e: ChangeEvent<HTMLSelectElement>) => {
    i18n.changeLanguage(e.target.value);
  };

  if (!state) return <></>;

  return (
    <BasicLayout title={t('title')}>
      <div className="bg-white p-6 rounded-md shadow-md">
        <form onSubmit={handleSubmit}>
          <CSRFToken />
          <div className="mx-auto md:w-100">
            <div className="mb-5 text-center">
              <div className="mb-3">
                {avatarPreview ? (
                  <img
                    src={avatarPreview}
                    alt={t('avatar.alt')}
                    className="w-32 h-32 rounded-full mx-auto object-cover border"
                  />
                ) : (
                  <div className="w-32 h-32 rounded-full mx-auto bg-gray-200 flex items-center justify-center border">
                    <span className="text-gray-400">{t('avatar.noImage')}</span>
                  </div>
                )}
              </div>
              <button
                type="button"
                className="btn btn-outline-secondary btn-sm"
                onClick={() => fileInputRef.current?.click()}
              >
                {t('avatar.select')}
              </button>
              <input
                type="file"
                ref={fileInputRef}
                className="hidden"
                accept="image/*"
                onChange={handleAvatarChange}
              />
            </div>

            <TextInput
              identity="name"
              controlType="text"
              label={t('form.name')}
              defaultValue={name}
              onChange={e => setName(e.target.value)}
              autoFocus={true}
              className="mb-5"
            />

            <div className="mb-5">
              <label className="block text-gray-700 text-sm font-bold mb-2">
                {t('form.email')}
              </label>
              <input type="text" className="form-control" value={state.auth.email || ''} disabled />
              <small className="text-gray-500">{t('form.emailNote')}</small>
            </div>

            <div className="mb-5">
              <label className="block text-gray-700 text-sm font-bold mb-2">{t('language')}</label>
              <select
                className="form-control"
                value={i18n.language}
                onChange={handleLanguageChange}
              >
                {languages.map(lang => (
                  <option key={lang.code} value={lang.code}>
                    {lang.label}
                  </option>
                ))}
              </select>
            </div>

            <div className="mt-3 text-center">
              <button type="submit" className="btn btn-primary">
                {t('form.submit')}
              </button>
            </div>
          </div>
        </form>
      </div>
    </BasicLayout>
  );
};

export default ProfilePage;
