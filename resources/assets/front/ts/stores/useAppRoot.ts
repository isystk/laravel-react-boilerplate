import { useEffect } from 'react';
import { useAppState, useAppDispatch } from './appContext';
import MainService from '@/services/main';

const useAppRoot = () => {
  const { root } = useAppState();
  const dispatch = useAppDispatch();

  const _setAppRoot = async (service: MainService) => {
    dispatch({ type: 'SET_STATE', payload: service });
    dispatch({ type: 'TOGGLE_STATE' });
  };

  useEffect(() => {
    const init = async () => {
      const _appRoot = new MainService(_setAppRoot);
      await _appRoot.setAppRoot();
    };
    if (!root) {
      init();
    }
  }, []);

  return root;
};

export default useAppRoot;
