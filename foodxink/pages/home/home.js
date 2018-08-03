// pages/home/home.js

const app = getApp()

Page({

  /**
   * 页面的初始数据
   */
  data: {
    userInfo: {},
    openUserInfo: false,
    appointlist: [{
        time: {
          date: "6月18号",
          week: "星期一",
          hour: "19:50"
        },
        location: {
          address: "北京市朝阳区望京园601号",
          errMsg: "chooseLocation:ok",
          latitude: 39.98994,
          longitude: 116.47884,
          name: "悠乐汇E座"
        },
        img: "/img/ceshi.jpg",
        href: "../logs/logs"
      },
      {
        time: {
          date: "6月18号",
          week: "星期一",
          hour: "19:50"
        },
        location: {
          address: "北京市朝阳区望京园601号",
          errMsg: "chooseLocation:ok",
          latitude: 39.98994,
          longitude: 116.47884,
          name: "悠乐汇E座"
        },
        img: "/img/ceshi2.jpg",
        href: "../logs/logs"
      },
      {
        time: {
          date: "6月18号",
          week: "星期一",
          hour: "19:50"
        },
        location: {
          address: "北京市朝阳区望京园601号",
          errMsg: "chooseLocation:ok",
          latitude: 39.98994,
          longitude: 116.67884,
          name: "悠乐汇E座"
        },
        img: "/img/ceshi.jpg",
        href: "../logs/logs"
      }
    ],
    recommendList: [
      [{
          time: {
            date: "6月18号",
            week: "星期一",
            hour: "19:50"
          },
          location: {
            ddress: "北京市朝阳区望京街10号",
            errMsg: "chooseLocation:ok",
            latitude: 39.99612,
            longitude: 116.58085,
            name: "望京SOHO"
          },
          img: "/img/ceshi2.jpg",
          res: "吃得次数更多",
          href: "../logs/logs",
          gps:0
        },
        {
          time: {
            date: "6月18号",
            week: "星期一",
            hour: "19:50"
          },
          location: {
            address: "北京市朝阳区望京园601号",
            errMsg: "chooseLocation:ok",
            latitude: 39.98994,
            longitude: 116.97884,
            name: "悠乐汇E座"
          },
          img: "/img/ceshi.jpg",
          res: "吃得次数更多",
          href: "../logs/logs",
          gps: 0
        },
      ],
      [{
          time: {
            date: "6月18号",
            week: "星期一",
            hour: "19:50"
          },
          location: {
            address: "北京市朝阳区望京园601号",
            errMsg: "chooseLocation:ok",
            latitude: 39.98994,
            longitude: 116.87884,
            name: "悠乐汇E座"
          },
          img: "/img/ceshi2.jpg",
          res: "吃得次数更多",
          href: "../logs/logs",
          gps: 0
        },
        {
          time: {
            date: "6月18号",
            week: "星期一",
            hour: "19:50"
          },
          location: {
            address: "北京市朝阳区望京园601号",
            errMsg: "chooseLocation:ok",
            latitude: 39.98994,
            longitude: 116.67884,
            name: "悠乐汇E座"
          },
          img: "/img/ceshi.jpg",
          res: "吃得次数更多",
          href: "../logs/logs",
          gps: 0
        },
      ],
      [{
        time: {
          date: "6月18号",
          week: "星期一",
          hour: "19:50"
        },
        location: {
          address: "北京市朝阳区望京园601号",
          errMsg: "chooseLocation:ok",
          latitude: 39.98994,
          longitude: 116.87884,
          name: "悠乐汇E座"
        },
        img: "/img/ceshi2.jpg",
        res: "吃得次数更多",
        href: "../logs/logs",
        gps: 0
      },
      {
        time: {
          date: "6月18号",
          week: "星期一",
          hour: "19:50"
        },
        location: {
          address: "北京市朝阳区望京园601号",
          errMsg: "chooseLocation:ok",
          latitude: 39.98994,
          longitude: 116.67884,
          name: "悠乐汇E座"
        },
        img: "/img/ceshi.jpg",
        res: "吃得次数更多",
        href: "../logs/logs",
        gps: 0
      },
      ],


    ]

  },
  //事件处理函数
  showUserInfo: function() {
    console.log(this.data.openUserInfo)
    this.setData({
      openUserInfo: !this.data.openUserInfo
    })

  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function() {
    if (app.globalData.userInfo) {
      this.setData({
        userInfo: app.globalData.userInfo,
        hasUserInfo: true
      })
    } else if (this.data.canIUse) {
      // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
      // 所以此处加入 callback 以防止这种情况
      app.userInfoReadyCallback = res => {
        this.setData({
          userInfo: res.userInfo,
          hasUserInfo: true
        })
      }
    } else {
      // 在没有 open-type=getUserInfo 版本的兼容处理
      wx.getUserInfo({
        success: res => {
          app.globalData.userInfo = res.userInfo
          this.setData({
            userInfo: res.userInfo,
            hasUserInfo: true
          })
        }
      })
    }






  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function() {

  //初始计算两点之间距离
    for (var val of this.data.recommendList) {
      for (var i of val) {
        i.gps = this.getDistance(39.98994, 116.47884, i.location.latitude, i.location.longitude);
      }
    }

    var b = this.data.recommendList;
    this.setData({
      recommendList: b
    })

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function() {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function() {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function() {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function() {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function() {

  },



  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function() {

  },
  getUserInfo: function(e) {
    console.log(e)
    app.globalData.userInfo = e.detail.userInfo
    this.setData({
      userInfo: e.detail.userInfo,
      hasUserInfo: true
    })
  },
  //获取当前位置坐标
  getCenterLocation: function() {
    this.mapCtx.getCenterLocation({
      success: function(res) {
        console.log(res.longitude)
        console.log(res.latitude)
        console.log(res)
      }
    })
  },
  //

  //获取两个地址之间的距离
  getDistance: function(lat1, lng1, lat2, lng2) {

    lat1 = lat1 || 0;

    lng1 = lng1 || 0;

    lat2 = lat2 || 0;

    lng2 = lng2 || 0;

    var rad1 = lat1 * Math.PI / 180.0;

    var rad2 = lat2 * Math.PI / 180.0;

    var a = rad1 - rad2;

    var b = lng1 * Math.PI / 180.0 - lng2 * Math.PI / 180.0;

    var r = 6378137;

    return (r * 2 * Math.asin(Math.sqrt(Math.pow(Math.sin(a / 2), 2) + Math.cos(rad1) * Math.cos(rad2) * Math.pow(Math.sin(b / 2), 2)))).toFixed(0)

  }

})