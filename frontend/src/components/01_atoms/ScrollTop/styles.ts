import { css, keyframes } from '@emotion/css'

export const scrollTop = css``

const fadeout = keyframes`
0% {
opacity: 1;
transform: translate(0, 0);
}
100% {
opacity: 0;
transform: translate(0, 50px);
}
`

export const hideButton = css`
  animation: ${fadeout} 0.5s both;
`

const fadein = keyframes`
0% {
opacity: 0;
transform: translate(0, 50px);
}
100% {
opacity: 1;
transform: translate(0, 0);
}
`

export const showButton = css`
  animation: ${fadein} 0.5s both;
`
