<?php exit() ?>--by ikita 61.238.99.170
local wayPointManager = WayPointManager()

wpsTime = {0,0,0,0,0,0,0,0,0,0}
lastDestx = {}
lastDestz = {}
suspectUpdate = {}
for i = 1, 10 , 1 do
	suspectUpdate[i] = {}
end

susList = {0,0,0,0,0,0,0,0,0,0}

function OnLoad()
	print("<font color='#b1a1c6'>ikita's BoL Friend Finder 1.6 started. The process would take a while. Click menu to show results.</font>")
	BolFinderConfig = scriptConfig("BoL Friend Finder", "bol_friend_finder_")
	BolFinderConfig:addParam("ffstart", "Show Friend results", SCRIPT_PARAM_ONOFF, false)
	BolFinderConfig:addParam("reset", "Reset Data", SCRIPT_PARAM_ONOFF, false)
	BolFinderConfig:addParam("samSize", "Sample Size", SCRIPT_PARAM_SLICE, 40, 1, 150, 0)
	BolFinderConfig:addParam("aveTime", "Average Time between updates", SCRIPT_PARAM_SLICE, 90, 1, 500, 0)
	BolFinderConfig:addParam("susCons", "Minimum consecutive suspicions", SCRIPT_PARAM_SLICE, 5, 1, 500, 0)
	BolFinderConfig:addParam("debugR", "Show all ratings (debug)", SCRIPT_PARAM_ONOFF, false)
	BolFinderConfig:addParam("resMenu", "Reset Menu to Default", SCRIPT_PARAM_ONOFF, false)
	BolFinderConfig.ffstart = false
	BolFinderConfig.reset = false
	BolFinderConfig.debugR = false
end

function OnTick()
	if BolFinderConfig.ffstart == true then
		local listed = false
		for i = 1, heroManager.iCount do
			local hero = heroManager:GetHero(i)
			if susList[i] > BolFinderConfig.susCons then
				listed = true
				print("<font color='#b1a1c6'>Found " .. hero.charName .. ", " .. hero.name .. ", rating " .. susList[i] .. "</font>")
			end
		end
		BolFinderConfig.ffstart = false
		if listed == false then
			print("<font color='#b1a1c6'>No Friends found.. yet</font>")
		end
	end
	if BolFinderConfig.debugR == true then
		for i = 1, heroManager.iCount do
			local hero = heroManager:GetHero(i)
			print("<font color='#b1a1c6'>" .. hero.charName .. ", " .. hero.name .. ", rating " .. susList[i] .. "</font>")
		end
		BolFinderConfig.debugR = false
	end
	
	if BolFinderConfig.reset == true then
		susList = {0,0,0,0,0,0,0,0,0,0}
		BolFinderConfig.reset = false
		print("<font color='#b1a1c6'>Friends data Reset</font>")
	end
	if BolFinderConfig.resMenu == true then
		BolFinderConfig.samSize = 40
		BolFinderConfig.aveTime = 90
		BolFinderConfig.susCons = 5
		BolFinderConfig.resMenu = false
	end
end

function checkAverage(heroid)
	for i = 1, heroManager.iCount, 1 do
		local hero = heroManager:GetHero(i)
		if i == heroid then
			if #suspectUpdate[i] == BolFinderConfig.samSize then
				local temp = 0
				for j = 1, #suspectUpdate[i], 1 do
					temp = temp + suspectUpdate[i][j]
				end
				--print(temp/#suspectUpdate[i]) --time between udpates, uncomment for debug
				if temp/#suspectUpdate[i] < BolFinderConfig.aveTime then -- this is the time between updates
					susList[i] = susList[i] + 1
					if susList[i] == BolFinderConfig.susCons + 1 then
						print("<font color='#b1a1c6'>Found " .. hero.charName .. ", " .. hero.name .. "</font>")
					end
				end
				suspectUpdate[i] = {}
			end
		end
	end
end

function OnDraw()
	for i = 1, heroManager.iCount do
		local hero = heroManager:GetHero(i)
		local point = wayPointManager:GetWayPoints(hero)		
		local maxp = 1
		for i, wp in ipairs(point) do maxp = i end
		if lastDestx[i] == nil then
			lastDestx[i] = point[maxp].x
			lastDestz[i] = point[maxp].z
			wpsTime[i] = GetTickCount()
		end
		if wpsTime[i] ~= nil and lastDestx[i] ~= nil and point[maxp].x ~= lastDestx[i] then -- i updated its waypoints
				table.insert(suspectUpdate[i],GetTickCount() - wpsTime[i])
			lastDestx[i] = point[maxp].x
			lastDestz[i] = point[maxp].z
			wpsTime[i] = GetTickCount()
			checkAverage(i)
		end
	end
end