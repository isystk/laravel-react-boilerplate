import styles from './styles.module.scss';
import Image from '@/components/atoms/Image';
import { Link } from 'react-router-dom';
import { Url } from '@/constants/url';
import Env from '@/constants/env';
import logoImage from '@/assets/images/logo.png';

export type Props = {
  hasLink?: boolean;
  src?: string;
};

const Logo = ({ hasLink = true, src }: Props) => {
  const imageSrc = src || (logoImage as string);
  return hasLink ? <LinkLogo src={imageSrc} /> : <NoLinkLogo src={imageSrc} />;
};

const LinkLogo = ({ src }: { src: string }) => {
  return (
    <Link to={Url.TOP} className={`flex items-center`}>
      <Image src={src} width={200} height={60} alt={Env.APP_NAME} className={styles.logoImage} />
    </Link>
  );
};

const NoLinkLogo = ({ src }: { src: string }) => {
  return (
    <Image src={src} width={200} height={60} alt={Env.APP_NAME} className={styles.logoImage} />
  );
};

export default Logo;
