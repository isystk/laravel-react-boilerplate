import CSRFToken from '@/components/atoms/CSRFToken';
import SessionAlert from '@/components/atoms/SessionAlert';
import BasicLayout from '@/components/templates/BasicLayout';
import useAppRoot from '@/hooks/useAppRoot';
import TextInput from '@/components/atoms/TextInput';

const ResetForm = () => {
  const [state] = useAppRoot();
  if (!state) return <></>;

  return (
    <BasicLayout title="パスワードリセット">
      <div className="bg-white p-6 rounded-md shadow-md ">
        <form method="POST" action="/forgot-password" id="login-form">
          <CSRFToken />
          <div className="mx-auto md:w-100">
            <SessionAlert target="status" className="mb-5" />
            <TextInput
              identity="email"
              controlType="email"
              label="メールアドレス"
              autoFocus={true}
              className="mb-5"
            />
            <div className="mt-3 text-center">
              <button type="submit" className="btn btn-primary mr-5">
                メールを送信する
              </button>
            </div>
          </div>
        </form>
      </div>
    </BasicLayout>
  );
};

export default ResetForm;
