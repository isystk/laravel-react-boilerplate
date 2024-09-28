import React, { FC } from 'react'
import { ContainerProps } from 'types'
import { connect } from '@/components/hoc'
import DataTable, {
  PaginationComponentProps,
  TableColumn,
} from 'react-data-table-component'
import * as _ from 'lodash'
import * as XLSX from 'xlsx'
import * as styles from './styles'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import {
  faFileCsv,
  faFileExcel,
  faFilePdf,
} from '@fortawesome/free-solid-svg-icons'
import jsPDF from 'jspdf'
import 'jspdf-autotable'
import { myFont } from '@/static/fonts/mplus.ts'

/** TableProps Props */
export type TableProps = {
  columns: TableColumn<Record<never, never>>[]
  data: Record<never, never>[]
}
/** Presenter Props */
export type PresenterProps = TableProps & {
  exportCsv
  exportExcel
  exportPdf
}

/** Presenter Component */
const TablePresenter: FC<PresenterProps> = ({
  columns,
  data,
  exportCsv,
  exportExcel,
  exportPdf,
}) => (
  <>
    <button
      className="bg-blue-500 text-white py-2 px-4 rounded w-full md:w-72 mb-2 "
      onClick={() => exportCsv()}
    >
      <FontAwesomeIcon icon={faFileCsv} className="pr-3" />
      CSVダウンロード
    </button>
    <button
      className="bg-blue-500 text-white py-2 px-4 rounded md:ml-4 w-full md:w-72 mb-2 "
      onClick={() => exportExcel()}
    >
      <FontAwesomeIcon icon={faFileExcel} className="pr-3" />
      Excelダウンロード
    </button>
    <button
      className="bg-blue-500 text-white py-2 px-4 rounded md:ml-4 w-full md:w-72 mb-2 "
      onClick={() => exportPdf()}
    >
      <FontAwesomeIcon icon={faFilePdf} className="pr-3" />
      PDFダウンロード
    </button>
    <DataTable
      className={`${styles.table}`}
      columns={columns}
      data={data}
      striped={true}
      pagination={true} // ページングを有効にする
      paginationDefaultPage={1} // 最初に表示するページを設定する
      paginationPerPage={5} // 1ページに表示する行数を設定する
      // paginationRowsPerPageOptions={[5, 10, 15, 20]} // 1ページに表示する行数の選択肢を設定する
      // paginationTotalRows={data.length} // 全行数を設定する
      // PaginationPageRange={5} // ページボタンを中心に5つだけ表示するようにする
      paginationComponent={({
        onChangePage,
        onChangeRowsPerPage,
        currentPage,
        rowsPerPage,
        rowCount,
      }: PaginationComponentProps) => {
        const numberOfPages = Math.ceil(rowCount / rowsPerPage)
        const handleClick = (page) => onChangePage(page, data.length)
        const pageButtons = []
        let startPage = Math.max(currentPage - 2, 1)
        let endPage = Math.min(currentPage + 2, numberOfPages)
        if (currentPage <= 2) {
          endPage = Math.min(5, numberOfPages)
        } else if (currentPage >= numberOfPages - 1) {
          startPage = Math.max(numberOfPages - 4, 1)
        }
        for (let i = startPage; i <= endPage; i++) {
          pageButtons.push(
            <button
              key={i}
              className={`inline-block font-bold px-4 py-2 ${
                currentPage === i
                  ? 'bg-blue-500 text-white'
                  : 'bg-gray-200 text-gray-700 '
              }`}
              onClick={() => handleClick(i)}
            >
              {i}
            </button>
          )
        }
        return (
          <div className="flex items-center justify-center mt-6 text-sm">
            <select
              className="mr-2 p-2 border rounded"
              value={rowsPerPage}
              onChange={(e) => {
                onChangeRowsPerPage(parseInt(e.target.value), currentPage)
              }}
            >
              {[5, 10, 15, 20].map((option) => (
                <option key={option} value={option}>
                  {option} 件表示
                </option>
              ))}
            </select>
            <button
              className={`inline-block bg-gray-200 text-gray-700 font-bold px-4 py-2 rounded-l ${
                currentPage === 1 ? 'opacity-50' : ''
              }`}
              onClick={() => handleClick(currentPage - 1)}
              disabled={currentPage === 1}
            >
              前へ
            </button>
            {pageButtons}
            <button
              className={`inline-block bg-gray-200 text-gray-700 font-bold px-4 py-2 rounded-r ${
                currentPage === numberOfPages ? 'opacity-50' : ''
              }`}
              onClick={() => handleClick(currentPage + 1)}
              disabled={currentPage === numberOfPages}
            >
              次へ
            </button>
          </div>
        )
      }}
    />
  </>
)

/** Container Component */
const TableContainer: React.FC<ContainerProps<TableProps, PresenterProps>> = ({
  presenter,
  columns,
  data,
  ...props
}) => {
  const exportCsv = () => {
    //表示されている列のインデックスを取得
    const visibleColumnIndexes = columns
      .filter((column) => column['hidden'] !== true)
      .map((column) => columns.indexOf(column))

    //表示されているデータを取得
    const r = data.map((item) =>
      visibleColumnIndexes.map(
        (index) =>
          typeof columns[index].selector === 'string' // プロパティ名が指定されている場合
            ? item[columns[index].selector]
            : typeof columns[index].cell === 'function'
            ? columns[index].cell(item) // cellを使用している場合
            : columns[index].selector(item) // クロージャが指定されている場合
      )
    )

    const records = {
      header: _.map(columns, 'name'),
      body: r,
    }

    // CSV形式にデータを加工
    const text = [records.header, ...records.body]
      .map((record) =>
        record
          .map((item) =>
            item === null
              ? '' //nullの場合は空文字列として扱う
              : typeof item === 'string'
              ? `"${item}"`
              : item
          )
          .join(',')
      )
      .join('\r\n')

    // 一覧のデータをダウンロードする
    const blob = new Blob(['\uFEFF' + text], { type: 'text/csv' })
    const url = (window.URL || window.webkitURL).createObjectURL(blob)
    const link = document.createElement('a')
    link.download = 'data.csv'
    link.href = url
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
  }

  const exportExcel = () => {
    //表示されている列のインデックスを取得
    const visibleColumnIndexes = columns
      .filter((column) => column['hidden'] !== true)
      .map((column) => columns.indexOf(column))

    //表示されているデータを取得
    const r = data.map((item) =>
      visibleColumnIndexes.map(
        (index) =>
          typeof columns[index].selector === 'string' // プロパティ名が指定されている場合
            ? item[columns[index].selector]
            : typeof columns[index].cell === 'function'
            ? columns[index].cell(item) // cellを使用している場合
            : columns[index].selector(item) // クロージャが指定されている場合
      )
    )

    const records = {
      header: _.map(columns, 'name'),
      body: r,
    }

    // KeyValue形式にデータを加工
    const text = records.body.map((record) => {
      return records.header.reduce((arr, key, index) => {
        arr[key] = record[index]
        return arr
      }, {})
    })

    // 一覧のデータをダウンロードする
    const worksheet = XLSX.utils.json_to_sheet(text)
    const workbook = XLSX.utils.book_new()
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1')
    const buffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' })
    const blob = new Blob([buffer], {
      type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    })
    const url = (window.URL || window.webkitURL).createObjectURL(blob)
    const link = document.createElement('a')
    link.download = 'data.xlsx'
    link.href = url
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
  }

  const exportPdf = () => {
    //表示されている列のインデックスを取得
    const visibleColumnIndexes = columns
      .filter((column) => column['hidden'] !== true)
      .map((column) => columns.indexOf(column))

    //表示されているデータを取得
    const r = data.map((item) =>
      visibleColumnIndexes.map(
        (index) =>
          typeof columns[index].selector === 'string' // プロパティ名が指定されている場合
            ? item[columns[index].selector]
            : typeof columns[index].cell === 'function'
            ? columns[index].cell(item) // cellを使用している場合
            : columns[index].selector(item) // クロージャが指定されている場合
      )
    )

    const unit = 'pt'
    const size = 'A4' // use A1, A2, A3 or A4
    const orientation = 'portrait' // use portrait or landscape
    const doc = new jsPDF(orientation, unit, size)

    // 日本語のフォントを設定
    // See https://zenn.dev/knaka0209/articles/8b4e0864d1b226
    doc.addFileToVFS('mplus.ttf', myFont)
    doc.addFont('mplus.ttf', 'mplus', 'normal')
    doc.setFont('mplus', 'normal')

    doc.autoTable({
      styles: {
        font: 'mplus',
      },
      columns: columns.map((col) => {
        return { header: col.name, dataKey: col.selector }
      }),
      body: r,
    })

    doc.save('data.pdf')
  }

  return presenter({
    columns,
    data,
    exportCsv,
    exportExcel,
    exportPdf,
    ...props,
  })
}

export default connect<TableProps, PresenterProps>(
  'Table',
  TablePresenter,
  TableContainer
)
