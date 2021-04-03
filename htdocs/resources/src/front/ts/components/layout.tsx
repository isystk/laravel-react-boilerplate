import React, { FC } from 'react'
import CommonHeader from './Commons/Header'
import CommonFooter from '../containers/Commons/Footer'
import PropTypes from 'prop-types'

const Layout: FC = props => (
  <>
    <CommonHeader />
    {props.children}
    <CommonFooter />
  </>
)

Layout.propTypes = {
  children: PropTypes.oneOfType([PropTypes.string, PropTypes.element]).isRequired,
}

export default Layout
