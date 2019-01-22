          @if(isset($id) && strlen($id))
          <div class="content_comment clear">
            <div data-num-posts="5" width="100%" data-href="{!!Site::urlCurrent()!!}" class="fb-comments fb_iframe_widget" fb-xfbml-state="rendered"></div>
          </div>
          


               <div id="comment_post_container" style="display:none;" class="clear comment_post_item_{{$id}}">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="text-success">15 Bình luận </span>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="widget-area no-padding blank">
                                        <div class="status-upload">
                                            <form>
                                                <textarea placeholder="Viết bình luận công khai" ></textarea>
                                                <ul>
                                                    <li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Audio"><i class="fa fa-music"></i></a></li>
                                                    <li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Video"><i class="fa fa-video-camera"></i></a></li>
                                                    <li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Sound Record"><i class="fa fa-microphone"></i></a></li>
                                                    <li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Picture"><i class="fa fa-picture-o"></i></a></li>
                                                </ul>
                                                <button type="submit" class="btn btn-success green"><i class="glyphicon glyphicon-comment"></i> Đăng</button>
                                            </form>
                                        </div><!-- Status Upload  -->
                                    </div><!-- Widget Area -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="blog-comment">
                                        <hr/>
                                        <ul class="comments">
                                        <li class="clearfix">
                                          <img src="http://bootdey.com/img/Content/user_1.jpg" class="avatar" alt="">
                                          <div class="post-comments">
                                              <p class="meta">Dec 18, 2014 <a href="#">JohnDoe</a> says : <i class="pull-right"><a href="#"><small>Reply</small></a></i></p>
                                              <p>
                                                  Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                  Etiam a sapien odio, sit amet
                                              </p>
                                          </div>
                                        </li>
                                        <li class="clearfix">
                                          <img src="http://bootdey.com/img/Content/user_2.jpg" class="avatar" alt="">
                                          <div class="post-comments">
                                              <p class="meta">Dec 19, 2014 <a href="#">JohnDoe</a> says : <i class="pull-right"><a href="#"><small>Reply</small></a></i></p>
                                              <p>
                                                  Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                  Etiam a sapien odio, sit amet
                                              </p>
                                          </div>

                                          <ul class="comments">
                                              <li class="clearfix">
                                                  <img src="http://bootdey.com/img/Content/user_3.jpg" class="avatar" alt="">
                                                  <div class="post-comments">
                                                      <p class="meta">Dec 20, 2014 <a href="#">JohnDoe</a> says : <i class="pull-right"><a href="#"><small>Reply</small></a></i></p>
                                                      <p>
                                                          Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                          Etiam a sapien odio, sit amet
                                                      </p>
                                                  </div>
                                              </li>
                                          </ul>
                                        </li>
                                        </ul>
                                        <div class="view-more-comment">
                                            <a class="btn btn-primary" href="#" title=""><i class="glyphicon glyphicon-repeat"></i> Tải thêm bình luận</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
               </div><!--comment_post_container-->
          @endif
