  <div class="row">
      <div class="col-sm-6 col-md-3">
          <div class="card card-stats card-round">
              <div class="card-body ">
                  <div class="row align-items-center">
                      <div class="col-icon">
                          <div class="icon-big text-center icon-primary bubble-shadow-small">
                              <i class="fas fa-users"></i>
                          </div>
                      </div>
                      <div class="col col-stats ml-3 ml-sm-0">
                          <div class="numbers">
                              <p class="card-category">Visitors</p>
                              <h4 class="card-title">1,294</h4>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="col-sm-6 col-md-3">
          <div class="card card-stats card-round">
              <div class="card-body">
                  <div class="row align-items-center">
                      <div class="col-icon">
                          <div class="icon-big text-center icon-info bubble-shadow-small">
                              <i class="far fa-newspaper"></i>
                          </div>
                      </div>
                      <div class="col col-stats ml-3 ml-sm-0">
                          <div class="numbers">
                              <p class="card-category">Subscribers</p>
                              <h4 class="card-title">1303</h4>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="col-sm-6 col-md-3">
          <div class="card card-stats card-round">
              <div class="card-body">
                  <div class="row align-items-center">
                      <div class="col-icon">
                          <div class="icon-big text-center icon-success bubble-shadow-small">
                              <i class="far fa-chart-bar"></i>
                          </div>
                      </div>
                      <div class="col col-stats ml-3 ml-sm-0">
                          <div class="numbers">
                              <p class="card-category">Sales</p>
                              <h4 class="card-title">$ 1,345</h4>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="col-sm-6 col-md-3">
          <div class="card card-stats card-round">
              <div class="card-body">
                  <div class="row align-items-center">
                      <div class="col-icon">
                          <div class="icon-big text-center icon-secondary bubble-shadow-small">
                              <i class="far fa-check-circle"></i>
                          </div>
                      </div>
                      <div class="col col-stats ml-3 ml-sm-0">
                          <div class="numbers">
                              <p class="card-category">Order</p>
                              <h4 class="card-title">576</h4>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <div class="row">
      <div class="col-md-8">
          <div class="card">
              <div class="card-header">
                  <div class="card-head-row">
                      <div class="card-title">User Statistics</div>
                      <div class="card-tools">
                          <a href="#" class="btn btn-info btn-border btn-round btn-sm mr-2">
                              <span class="btn-label">
                                  <i class="fa fa-pencil"></i>
                              </span>
                              Export
                          </a>
                          <a href="#" class="btn btn-info btn-border btn-round btn-sm">
                              <span class="btn-label">
                                  <i class="fa fa-print"></i>
                              </span>
                              Print
                          </a>
                      </div>
                  </div>
              </div>
              <div class="card-body">
                  <div class="chart-container" style="min-height: 375px">
                      <canvas id="statisticsChart"></canvas>
                  </div>
                  <div id="myChartLegend"></div>
              </div>
          </div>
      </div>
      <div class="col-md-4">
          <div class="card card-secondary">
              <div class="card-header">
                  <div class="card-title">Daily Sales</div>
                  <div class="card-category">March 25 - April 02</div>
              </div>
              <div class="card-body pb-0">
                  <div class="mb-4 mt-2">
                      <h1>$4,578.58</h1>
                  </div>
                  <div class="pull-in">
                      <canvas id="dailySalesChart"></canvas>
                  </div>
              </div>
          </div>
          <div class="card card-info bg-info-gradient">
              <div class="card-body">
                  <h4 class="mb-1 fw-bold">Tasks Progress</h4>
                  <div id="task-complete" class="chart-circle mt-4 mb-3"></div>
              </div>
          </div>
      </div>
  </div>