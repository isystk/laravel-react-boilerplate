import ReactDOM from 'react-dom'
import React, { FC, useRef, useState, useEffect } from 'react'

const Portal: FC = ({ children }) => {
  const ref = useRef<HTMLElement>()
  const [mounted, setMounted] = useState(false)

  useEffect(() => {
    const current = document.querySelector<HTMLElement>('#react-root')
    if (current) {
      console.log('current', current)
      ref.current = current
    }
    setMounted(true)
  }, [])

  console.log('mounted', mounted)
  return mounted ? ReactDOM.createPortal(<>{children}</>, ref.current ? ref.current : new Element()) : null
}

export default Portal
