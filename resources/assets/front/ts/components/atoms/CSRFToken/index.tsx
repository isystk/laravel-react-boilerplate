import useAppRoot from "@/stores/useAppRoot";

const CSRFToken = () => {
    const appRoot = useAppRoot();
    if (!appRoot) return <></>;

    const { csrf } = appRoot.auth;
    return <input type="hidden" name="_token" defaultValue={csrf || ''} />;
};

export default CSRFToken;
