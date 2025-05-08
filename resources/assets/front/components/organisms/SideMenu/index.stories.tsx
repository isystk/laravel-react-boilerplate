import SideMenu from './index';
import useAppRoot from '@/states/useAppRoot';
import { JSX } from 'react';
import { MINIMAL_VIEWPORTS } from '@storybook/addon-viewport';

export default {
  title: 'Components/Organisms/SideMenu',
  component: SideMenu,
  tags: ['autodocs'],
  parameters: {
    viewport: {
      viewports: MINIMAL_VIEWPORTS,
    },
  },
};

export const Default: { render: () => JSX.Element } = {
  render: () => {
    const Component = () => {
      const { state } = useAppRoot();
      if (!state) return <></>;
      return (
        <SideMenu
          text="メニュー"
          items={[
            { text: 'ホーム', onClick: () => console.log('ホーム') },
            { text: 'プロフィール', onClick: () => console.log('プロフィール') },
          ]}
        />
      );
    };
    return <Component />;
  },
};
