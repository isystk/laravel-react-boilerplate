import { css, keyframes } from '@emotion/css'

export const fadeIn = css`
  opacity: 0;
  transform: translate(0, 50px);
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

export const scrollin = css`
  animation: ${fadein} 0.5s both;
`

export const delay = (props) => css`
  animation-delay: ${props.delay || '0s'};
`
