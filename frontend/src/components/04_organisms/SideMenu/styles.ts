import { css, keyframes } from '@emotion/css'

export const sideMenu = css`
  position: absolute;
  @media (min-width: 768px) {
    position: static;
  }
`

export const menuClose = css`
  width: 0;
  opacity: 0;
  transition: 0.5s;
  @media (min-width: 768px) {
    width: 250px;
    opacity: 1;
  }
`
export const menuOpen = css`
  width: 70%;
  opacity: 1;
  @media (min-width: 768px) {
    width: 250px;
    opacity: 1;
  }
`
