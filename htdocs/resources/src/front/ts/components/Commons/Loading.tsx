import React, { FC } from 'react'
import { useSelector } from 'react-redux'
import Portal from './Portal'
import { Parts } from '../../store/StoreTypes'

const Loading: FC = () => {
  const { isShowLoading } = useSelector(parts)

  console.log('isShowLoading', isShowLoading)
  return (
    <Portal>
      {isShowLoading && (
        <div id="site_loader_overlay">
          <div className="site_loader_spinner"></div>
        </div>
      )}
    </Portal>
  )
}

const parts = (state): Parts => state.parts

export default Loading
