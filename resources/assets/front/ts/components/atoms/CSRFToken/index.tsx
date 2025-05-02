import useAppRoot from '@/states/useAppRoot';

const CSRFToken = () => {
  const [state] = useAppRoot();
  if (!state) return <></>;

  const { csrf } = state.auth;
  return <input type="hidden" name="_token" defaultValue={csrf || ''} />;
};

export default CSRFToken;
