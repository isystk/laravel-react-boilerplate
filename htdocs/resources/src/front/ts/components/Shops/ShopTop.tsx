import React, { FC, useEffect } from 'react'
import { useSelector, useDispatch } from 'react-redux'
import * as _ from 'lodash'
import { API } from '../../utilities'
import { readLikesAsync, addLikeAsync, removeLikeAsync } from '../../reducers/likes'
import { API_ENDPOINT } from '../../common/constants/api'
import Pagination from 'react-js-pagination'
import { URL } from '../../common/constants/url'
import { push } from 'connected-react-router'
import TopCarousel from './TopCarousel'
import { Stock } from '../../store/StoreTypes'

type State = {
  stocks: {
    total: number
    current_page: number
    data: Stock[]
  }
  router
  likes
}

const ShopTop: FC = () => {
  const { search } = useSelector((state: State) => ({
    pathname: state.router.location.pathname,
    search: state.router.location.search,
    hash: state.router.location.hash,
  }))
  const stocks = useSelector((state: State) =>
    _.map(state.stocks.data, stock => ({
      ...stock,
      price: stock.price + '円',
      isLike: state.likes.data.includes(stock.id + ''),
    })),
  )
  const { total, current_page } = useSelector((state: State) => ({
    total: state.stocks.total,
    current_page: state.stocks.current_page,
  }))
  const dispatch = useDispatch()

  useEffect(() => {
    // 商品データを取得する
    ;(async () => {
      const response = await API.get(`${API_ENDPOINT.SHOPS}${search}`)
      dispatch({ type: 'READ_STOCKS', response })
    })()

    // お気に入りデータを取得する
    dispatch(readLikesAsync())
  }, [])

  const renderStocks = (): JSX.Element => (
    <>
      {stocks.map((stock, index) => (
        <div className="block01_item" key={index}>
          <div className="text-right mb-2">
            <a
              href="#"
              onClick={e => {
                e.preventDefault()
                if (stock.isLike) {
                  dispatch(removeLikeAsync(stock.id))
                } else {
                  dispatch(addLikeAsync(stock.id))
                }
              }}
              className={`btn btn-sm ${stock.isLike ? 'btn-success' : 'btn-secondary'}`}
              data-id="{stock.id}"
            >
              気になる
            </a>
          </div>
          <img src={`/uploads/stock/${stock.imgpath}`} alt="" className="block01_img" />
          <p>{stock.name}</p>
          <p className="c-red">{stock.price}</p>
          <p className="mb20">{stock.detail} </p>
          <form action="/shop/addcart" method="post">
            <input type="hidden" name="stock_id" value={stock.id} />

            {stock.quantity === 0 ? (
              <input type="button" value="カートに入れる（残り0個）" className="btn-gray" />
            ) : (
              <input
                type="button"
                value={`カートに入れる（残り${stock.quantity}個）`}
                className="btn-01"
                onClick={() => {
                  ;(async () => {
                    try {
                      const response = await API.post(API_ENDPOINT.ADD_MYCART, {
                        stock_id: stock.id,
                      })
                      if (response.result) {
                        dispatch({ type: 'READ_CARTS', response })
                        dispatch(push(URL.MYCART))
                      }
                    } catch (e) {
                      dispatch(push(URL.LOGIN))
                    }
                  })()
                }}
              />
            )}
          </form>
        </div>
      ))}
    </>
  )

  const renderPaging = (): JSX.Element => {
    return (
      <Pagination
        activePage={current_page}
        itemsCountPerPage={6}
        totalItemsCount={total}
        pageRangeDisplayed={3}
        onChange={handlePageChange}
        itemClass="page-item"
        linkClass="page-link"
      />
    )
  }

  const handlePageChange = async (pageNo: any) => {
    // 商品データを取得する
    const response = await API.get(`${API_ENDPOINT.SHOPS}?page=${pageNo}`)
    dispatch({ type: 'READ_STOCKS', response })
    dispatch(push(`${URL.TOP}?page=${pageNo}`))
  }

  return (
    <React.Fragment>
      <div className="contentsArea">
        <TopCarousel />
        <div className="">
          <div className="block01">{renderStocks()}</div>
          <div className="mt40">{renderPaging()}</div>
        </div>
      </div>
    </React.Fragment>
  )
}

export default ShopTop
