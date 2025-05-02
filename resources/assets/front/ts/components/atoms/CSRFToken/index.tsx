import useAppRoot from '@/stores/useAppRoot';

const CSRFToken = () => {
  const [state, service] = useAppRoot();
  if (!state) return <></>;

  const { csrf } = state.auth;
  return <input type="hidden" name="_token" defaultValue={csrf || ''} />;
};

export default CSRFToken;
