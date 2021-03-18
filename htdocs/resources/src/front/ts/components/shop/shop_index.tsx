import * as React from 'react'
import { connect, MapStateToProps, MapDispatchToProps } from 'react-redux'
import * as _ from 'lodash'
import { Link } from 'react-router-dom'
import { URL } from '../../common/constants/url'

import { readShops, readLikes } from '../../actions'
import { Stocks, Likes } from '../../store/StoreTypes'

interface IProps {
  stocks: Stocks
  likes: Likes
  readShops
  readLikes
}

export class StocksIndex extends React.Component<IProps> {
  componentDidMount(): void {
    // 商品データを取得する
    this.props.readShops()

    // お気に入りデータを取得する
    this.props.readLikes()
  }

  renderStocks(): JSX.Element {
    return _.map(this.props.stocks, (stock, index) => (
      <div className="block01_item" key={index}>
        <div className="text-right mb-2">
          <a href="#" className={`btn btn-sm ${stock.isLike ? 'btn-success' : 'btn-secondary'}`} data-id="{stock.id}">
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
            <input type="submit" value={`カートに入れる（残り${stock.quantity}個）`} className="btn-01" />
          )}
        </form>
      </div>
    ))
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
            <div className="mt40">{/*
                  {{ $stocks->links() }}
                  */}</div>
          </div>
        </div>
      </React.Fragment>
    )
  }
}

const mapStateToProps = (state, ownProps) => {
  const { total, current_page, ...stocks } = state.stocks
  const likes = state.likes
  return {
    stocks: _.map(stocks.data, function(stock) {
      // 表示用にデータを加工
      return {
        ...stock,
        price: stock.price + '円',
        isLike: likes.data.contain(stock.id),
      }
    }),
  }
}

const mapDispatchToProps = { readShops, readLikes }

export default connect(mapStateToProps, mapDispatchToProps)(StocksIndex)
