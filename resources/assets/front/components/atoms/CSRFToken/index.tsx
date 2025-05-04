const CSRFToken = () => {
  const token = document.head.querySelector<HTMLMetaElement>('meta[name="csrf-token"]');
  return <input type="hidden" name="_token" defaultValue={token?.content} />;
};

export default CSRFToken;
