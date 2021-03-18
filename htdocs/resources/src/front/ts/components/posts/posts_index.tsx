import * as React from "react";
import { connect, MapStateToProps, MapDispatchToProps } from "react-redux";
import * as _ from "lodash";
import { Link } from "react-router-dom";
import { URL } from "../../common/constants/url";

import { readPosts, showMv, hideMv } from "../../actions";
import { Posts } from "../../store/StoreTypes";

interface IProps {
  posts: Posts;
  readPosts;
  showMv;
  hideMv;
}

interface IState {
}

export class PostsIndex extends React.Component<IProps, IState> {

  componentDidMount(): void {
    // すべての投稿データを取得する
    this.props.readPosts();
    // メインビジュアルを表示する
    this.props.showMv();
  }

  componentWillUnmount(): void {
    // メインビジュアルを非表示にする
    this.props.hideMv();
  }

  renderPosts(): JSX.Element {
    return _.map(this.props.posts, (post) => (
      <section key={post.postId}>
        <Link to={`${URL.POSTS}/${post.postId}`}>
          <div className="entry-header">
            <div className="category_link">{post.tagName}</div>
            <h2 className="entry-title">{post.title}</h2>
            <div className="entry-meta">
              <span>
                {post.registTimeMMDD}
              </span>
            </div>
          </div>
          <div className="entry-content">
            <img alt="sample1" width="300" height="174" src={post.imageUrl} className="attachment-medium size-medium wp-post-image" />
            <p>{post.text}</p>
            <div className="clearfix"></div>
          </div>
        </Link>
      </section>
    ));
  }

  render(): JSX.Element {

    return (
      <React.Fragment>
      <div className="contentsArea">
      </div>
      </React.Fragment>
    );
  }
}

const mapStateToProps = (state, ownProps) => {
  return {
    posts: _.map(state.posts, function (post) {
      return {
        postId: post.postId,
        tagName: (post.tagNameList && 0<post.tagNameList.length) ? post.tagNameList[0] : '',
        title: post.title,
        text: post.text,
        registTimeMMDD: post.registTimeMMDD,
        imageUrl: (post.imageList && 0<post.imageList.length) ? post.imageList[0].imageUrl : ''
      };
    })
  };
};

const mapDispatchToProps = { readPosts, showMv, hideMv };

export default connect(mapStateToProps, mapDispatchToProps)(PostsIndex);
