import * as React from 'react'
import { connect } from 'react-redux'
import * as _ from 'lodash'

import { showOverlay, hideOverlay } from '../../actions'
import Portal from './Portal'

interface IProps {
  parts
  hideOverlay
  children
}

const Modal = (props: IProps) => {
  const { parts } = props

  const onClose = e => {
    e.preventDefault()
    props.hideOverlay()
  }

  return (
    <Portal>
      {parts.isShowOverlay && <div id="overlay-background"></div>}
      <div className={`isystk-overlay ${parts.isShowOverlay ? 'open' : ''}`}>
        <button type="button" className="close" aria-label="Close" onClick={onClose}>
          <span aria-hidden="true">&times;</span>
        </button>
        {props.children}
      </div>
    </Portal>
  )
}

const mapStateToProps = state => {
  return {
    parts: state.parts,
  }
}

const mapDispatchToProps = { showOverlay, hideOverlay }

export default connect(mapStateToProps, mapDispatchToProps)(Modal)
