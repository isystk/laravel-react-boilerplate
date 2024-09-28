import React from 'react'
import { useTranslation } from 'react-i18next'

export const useI18n = (type) => {
  const { t } = useTranslation(type)
  const tr = (s) => {
    const lines = t(s).split('\n')
    if (lines.length === 1) return lines[0]
    return lines.map((line, index) => (
      <React.Fragment key={index}>
        {index !== 0 && <br />}
        {line}
      </React.Fragment>
    ))
  }
  return { t: tr }
}
