import React, { FC, useEffect, useRef } from 'react'
import { ContainerProps } from 'types'
import { connect } from '@/components/hoc'
import Chart from 'chart.js/auto'

/** LineChartProps Props */
export type LineChartProps = {
  data: Array<number>
  labels: Array<string>
  title: string
  color: string
}
/** Presenter Props */
export type PresenterProps = LineChartProps & {
  canvasRef: HTMLCanvasElement
}

/** Presenter Component */
const LineChartPresenter: FC<PresenterProps> = ({ canvasRef }) => {
  return (
    <>
      <canvas ref={canvasRef}></canvas>
    </>
  )
}

/** Container Component */
const LineChartContainer: React.FC<
  ContainerProps<LineChartProps, PresenterProps>
> = ({ presenter, data, labels, title, color, ...props }) => {
  const canvasRef = useRef<HTMLCanvasElement | null>(null)

  useEffect(() => {
    const lineChart = new Chart(canvasRef.current!, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [
          {
            label: title,
            data: data,
            fill: false,
            borderColor: color,
          },
        ],
      },
    })
    return () => {
      lineChart.destroy()
    }
  }, [data, labels, title, color])

  return presenter({
    canvasRef,
    ...props,
  })
}

export default connect<LineChartProps, PresenterProps>(
  'LineChart',
  LineChartPresenter,
  LineChartContainer
)
