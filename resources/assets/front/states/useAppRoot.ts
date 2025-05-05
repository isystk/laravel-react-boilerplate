import { useEffect, useMemo, useCallback } from 'react';
import { useAppState, useAppDispatch } from '@/states/AppContext';
import MainService from '@/services/main';
import RootState from '@/states/root';

const useAppRoot = (): { state: RootState | null; service: MainService } => {
  const { root: state } = useAppState();
  const dispatch = useAppDispatch();

  const setRootState = useCallback(
    async (root: RootState) => {
      dispatch({ type: 'SET_STATE', payload: root });
      dispatch({ type: 'TOGGLE_STATE' });
    },
    [dispatch],
  );

  useEffect(() => {
    const init = async () => {
      const _root = new RootState();
      await setRootState(_root);
    };

    if (!state) {
      init();
    }
  }, [state, setRootState]);

  const service = useMemo(() => new MainService(state, setRootState), [state, setRootState]);

  return { state, service };
};

export default useAppRoot;
