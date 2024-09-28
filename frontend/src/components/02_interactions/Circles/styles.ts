import { css, keyframes } from '@emotion/css'

export const area = css`
  position: relative;
  width: 100%;
  height: 100%;
  padding: 0;
  margin: 0;
`

export const circles = css`
  position: absolute;
  top: 0;
  left: 0;
  overflow: hidden;
  width: 100%;
  height: 100%;
`

const fadeOutRotation = keyframes`
0% {
  transform: translateY(0) rotate(0deg);
  opacity: 1;
  border-radius: 0;
}
100% {
  transform: translateY(-1000px) rotate(720deg);
  opacity: 0;
  border-radius: 50%;
}
`

const circlesLi = css`
  position: absolute;
  display: block;
  list-style: none;
  width: 20px;
  height: 20px;
  background: #eee;
  animation: ${fadeOutRotation} 25s linear infinite;
  bottom: -150px;
`

export const circlesLi1 = css([
  circlesLi,
  ` left: 25%; width: 80px; height: 80px; animation-delay: 0s; animation-duration: 0s;`,
])
export const circlesLi2 = css([
  circlesLi,
  ` left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s;`,
])
export const circlesLi3 = css([
  circlesLi,
  ` left: 70%; width: 20px; height: 20px; animation-delay: 4s; animation-duration: 0s;`,
])
export const circlesLi4 = css([
  circlesLi,
  ` left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s;`,
])
export const circlesLi5 = css([
  circlesLi,
  ` left: 65%; width: 20px; height: 20px; animation-delay: 0s; animation-duration: 0s;`,
])
export const circlesLi6 = css([
  circlesLi,
  ` left: 75%; width: 110px; height: 110px; animation-delay: 3s; animation-duration: 0s;`,
])
export const circlesLi7 = css([
  circlesLi,
  ` left: 35%; width: 150px; height: 150px; animation-delay: 7s; animation-duration: 0s;`,
])
export const circlesLi8 = css([
  circlesLi,
  ` left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 45s;`,
])
export const circlesLi9 = css([
  circlesLi,
  ` left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 35s;`,
])
export const circlesLi10 = css([
  circlesLi,
  ` left: 85%; width: 150px; height: 150px; animation-delay: 0s; animation-duration: 11s;`,
])
