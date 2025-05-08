import styles from './styles.module.scss';
import Image from '@/components/atoms/Image';
import { Link } from 'react-router-dom';
import { Url } from '@/constants/url';
import Env from '@/constants/env';
import logoImage from '@/assets/images/logo.png';

export type Props = {
  hasLink?: boolean;
};

const Logo = ({ hasLink = true }: Props) => {
  return hasLink ? <LinkLogo /> : <NoLinkLogo />;
};

const LinkLogo = () => {
  return (
    <Link to={Url.TOP} className={`flex items-center`}>
      <Image
        src={logoImage as string}
        width={200}
        height={60}
        alt={Env.APP_NAME}
        className={styles.logoImage}
      />
    </Link>
  );
};

const NoLinkLogo = () => {
  return (
    <Image
      src={logoImage as string}
      width={200}
      height={60}
      alt={Env.APP_NAME}
      className={styles.logoImage}
    />
  );
};

export default Logo;
