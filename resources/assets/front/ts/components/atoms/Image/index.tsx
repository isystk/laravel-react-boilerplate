
type Props = {
    src: string,
    alt?: string,
    width: number,
    height: number,
    loading?: "eager" | "lazy",
    className?: string
};

const Image = ({ src, alt, loading, ...props }: Props) => {
    const subDirectory = import.meta.env.SUB_DIRECTORY || "";
    return (
        <>
            <img
                src={`${subDirectory}${src}`}
                alt={alt || ''}
                loading={loading || 'lazy'}
                {...props}
            />
        </>
    );
};

export default Image;

