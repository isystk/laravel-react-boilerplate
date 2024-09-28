import React, { FC } from 'react'

import useAppRoot from '@/stores/useAppRoot'
import LandingPageTemplate, {
  type LandingPageTemplateProps,
} from '@/components/06_templates/LandingPageTemplate'
import { useRouter } from 'next/router'
import ErrorTemplate, {
  ErrorTemplateProps,
} from '@/components/06_templates/ErrorTemplate'
const Index: FC = () => {
  const main = useAppRoot()
  const {
    query: { id: productId },
  } = useRouter()

  if (productId !== 'prod_NpvV9ohJIlgElI') {
    const props: ErrorTemplateProps = { statusCode: 404 }
    return <ErrorTemplate {...props} />
  }

  if (!main) return <></>
  const props: LandingPageTemplateProps = { main }
  return <LandingPageTemplate {...props} />
}
export default Index
