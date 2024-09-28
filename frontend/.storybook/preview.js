import '../src/assets/sass/app.scss';
import {Provider} from "react-redux";
import {store} from '../src/stores/appSlice'
import { RouterContext } from "next/dist/shared/lib/router-context"

export const parameters = {
  actions: { argTypesRegex: "^on[A-Z].*" },
  controls: {
    matchers: {
      color: /(background|color)$/i,
      date: /Date$/,
    },
  },
  nextRouter: {
    Provider: RouterContext.Provider,
  },
}

const withProvider = (StoryFn, context) => {
  return (
    <Provider store={store}>
      <StoryFn />
    </Provider>
  )
}

/** Decorators */
export const decorators = [withProvider]