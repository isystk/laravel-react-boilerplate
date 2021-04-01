import React, { FC } from 'react'
import { useDispatch, useSelector } from 'react-redux'
import Portal from './Portal'
import { Parts } from '../../store/StoreTypes'
import { hideOverlay } from '../../actions'
import PropTypes from 'prop-types'

const Modal: FC = props => {
  const dispatch = useDispatch()
  const { isShowOverlay } = useSelector(parts)

  const onClose = e => {
    e.preventDefault()
    dispatch(hideOverlay())
  }

  return (
    <Portal>
      {isShowOverlay && <div id="overlay-background"></div>}
      <div className={`isystk-overlay ${isShowOverlay ? 'open' : ''}`}>
        <button type="button" className="close" aria-label="Close" onClick={onClose}>
          <span aria-hidden="true">&times;</span>
        </button>
        {props.children}
      </div>
    </Portal>
  )
}

const parts = (state): Parts => state.parts

Modal.propTypes = {
  children: PropTypes.oneOfType([PropTypes.string, PropTypes.element]).isRequired,
}

export default Modal
