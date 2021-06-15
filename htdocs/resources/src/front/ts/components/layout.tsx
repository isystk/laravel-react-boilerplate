import React, { FC, useEffect } from 'react'
import { useDispatch } from 'react-redux'
import { hideLoading } from '../actions'
import CommonHeader from './Commons/Header'
import CommonFooter from '../containers/Commons/Footer'
import PropTypes from 'prop-types'
import Loading from './Commons/Loading'

const Layout: FC = props => {
  const dispatch = useDispatch()

  useEffect(() => {
    ;(async () => {
      // ローディングを非表示にする
      dispatch(hideLoading())
    })()
  }, [])

  return (
    <>
      <CommonHeader />
      {props.children}
      <CommonFooter />
      <Loading />
    </>
  )
}

Layout.propTypes = {
  children: PropTypes.oneOfType([PropTypes.string, PropTypes.element]).isRequired,
}

export default Layout
