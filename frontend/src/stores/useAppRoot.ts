import { useSelector, useDispatch } from 'react-redux'
import MainService from '@/services/main'
import { forceRender, setAppRoot, type RootState, type App } from './appSlice'

const useAppRoot = () => {
  const dispatch = useDispatch()
  const { root } = useSelector<RootState, App>((state) => state.app)

  const _setAppRoot = (appRoot: MainService) => {
    dispatch(setAppRoot(appRoot))
    dispatch(forceRender())
  }

  const init = () => {
    const _appRoot = new MainService(_setAppRoot)
    _appRoot.setAppRoot()
    return _appRoot
  }
  if (!root) {
    return init()
  }

  return root
}

export default useAppRoot
