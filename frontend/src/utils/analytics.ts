import ReactGA from 'react-ga'
import { GA_TRAKING_ID } from '@/constants'

function initGA() {
  ReactGA.initialize(GA_TRAKING_ID)
}

function logPageView() {
  ReactGA.set({ page: window.location.pathname + window.location.search })
  ReactGA.pageview(window.location.pathname + window.location.search)
}

export { initGA, logPageView }
