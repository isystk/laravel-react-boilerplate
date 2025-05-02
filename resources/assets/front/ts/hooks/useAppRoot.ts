import { useEffect } from 'react';
import { useAppState, useAppDispatch } from '@/state/AppContext';
import MainService from '@/services/main';
import RootState from '@/state/root';

const useAppRoot = (): [RootState | null, MainService] => {
  const { root: state } = useAppState();
  const dispatch = useAppDispatch();

  const setRootState = async (root: RootState) => {
    dispatch({ type: 'SET_STATE', payload: root });
    dispatch({ type: 'TOGGLE_STATE' });
  };

  useEffect(() => {
    const init = async () => {
      const _root = new RootState();
      await setRootState(_root);
    };

    if (!state) {
      init();
    }
  }, []);

  const service = new MainService(state, setRootState);

  return [state, service];
};

export default useAppRoot;
