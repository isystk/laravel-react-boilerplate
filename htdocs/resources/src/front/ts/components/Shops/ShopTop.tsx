import * as React from 'react'
import { connect } from 'react-redux'
import * as _ from 'lodash'
import Pagination from 'react-js-pagination';
import { push } from "connected-react-router"

import { readShops, readLikes, addLike, removeLike, addCart } from '../../actions'
import { Stocks, Likes, Page } from '../../store/StoreTypes'

interface IProps {
  stocks: Stocks
  likes: Likes
  paging: Page
  push
  url
  readShops
  readLikes
  addLike
  removeLike
  addCart
}

export class ShopTop extends React.Component<IProps> {

  constructor(props) {
    super(props);

    // 商品データを取得する
    this.props.readShops(this.props.url.search)

    this.handlePageChange=this.handlePageChange.bind(this);
  }


  componentDidMount(): void {
    
    // お気に入りデータを取得する
    this.props.readLikes()
  }

  renderStocks(): JSX.Element {
    return _.map(this.props.stocks, (stock, index) => (
      <div className="block01_item" key={index}>
        <div className="text-right mb-2">
          <a href="#" onClick={
                e => {
                  e.preventDefault()
                  if ( stock.isLike ) {
                    this.props.removeLike(stock.id)
                  } else {
                    this.props.addLike(stock.id)
                  }
                }
              } className={`btn btn-sm ${stock.isLike ? 'btn-success' : 'btn-secondary'}`} data-id="{stock.id}">
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
            <input type="button" value={`カートに入れる（残り${stock.quantity}個）`} className="btn-01" onClick={() => {
              this.props.addCart(stock.id);
            }}/>
          )}
        </form>
      </div>
    ))
  }

  renderPaging(): JSX.Element {

    const { total, current_page} = this.props.paging

    return <Pagination
      activePage={current_page}
      itemsCountPerPage={6}
      totalItemsCount={total}
      pageRangeDisplayed={3}
      onChange={this.handlePageChange}
      itemClass='page-item'
      linkClass='page-link'
    />
  }

  handlePageChange(pageNo) {
    // 商品データを取得する
    this.props.readShops(`?page=${pageNo}`)
  }

  render(): JSX.Element {
    return (
      <React.Fragment>
      <div className="contentsArea">
        <div id="link01" className="carousel slide mainBunner" data-ride="carousel">
          <div className="carousel-inner">
            <div className="carousel-item active">
              <img src="/assets/front/image/bunner_01.jpg" alt="" />
            </div>
            <div className="carousel-item">
              <img src="/assets/front/image/bunner_02.jpg" alt="" />
            </div>
            <a className="carousel-control-prev" href="#link01" role="button" data-slide="prev">
              <span className="carousel-control-prev-icon" aria-hidden="true"></span>
              <span className="sr-only">Previous</span>
            </a>
            <a className="carousel-control-next" href="#link01" role="button" data-slide="next">
              <span className="carousel-control-next-icon" aria-hidden="true"></span>
              <span className="sr-only">Next</span>
            </a>
          </div>
        </div>
        <div className="">
          <div className="block01">{this.renderStocks()}</div>
          <div className="mt40">{this.renderPaging()}</div>
        </div>
      </div>
      </React.Fragment>
    )
  }
}

const mapStateToProps = (state, ownProps) => {
  console.log("mapStateToProps", state);
  const { total, current_page, ...stocks } = state.stocks
  const likes = state.likes
  return {
    stocks: _.map(stocks.data, function(stock) {
      // 表示用にデータを加工
      return {
        ...stock,
        price: stock.price + '円',
        isLike: likes.data.includes(stock.id+''),
      }
    }),
    paging: {
      total: total,
      current_page: current_page
    },
    url: {
      pathname: state.router.location.pathname,
      search: state.router.location.search,
      hash: state.router.location.hash,
    }
  }
}

const mapDispatchToProps = { push, readShops, readLikes, addLike, removeLike, addCart }

export default connect(mapStateToProps, mapDispatchToProps)(ShopTop)
